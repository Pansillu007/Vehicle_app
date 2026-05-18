<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vehicle extends Model
{
    use HasFactory;

    protected static function newFactory()
    {
        return \Database\Factories\VehicleFactory::new();
    }

    protected $fillable = [
        'user_id',
        'name',
        'make',
        'model',
        'year',
        'number_plate',
        'color',
        'mileage',
        'fuel_type',
        'vin_number',
    ];

    protected $casts = [
        'year' => 'integer',
        'mileage' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function serviceRecords()
    {
        return $this->hasMany(ServiceRecord::class);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', "%{$search}%")
            ->orWhere('make', 'like', "%{$search}%")
            ->orWhere('model', 'like', "%{$search}%")
            ->orWhere('number_plate', 'like', "%{$search}%");
    }

    public function scopeFilterByMake($query, $make)
    {
        if ($make) {
            return $query->where('make', $make);
        }
        return $query;
    }

    public function scopeFilterByYear($query, $year)
    {
        if ($year) {
            return $query->where('year', $year);
        }
        return $query;
    }

    public function scopeFilterByFuelType($query, $fuelType)
    {
        if ($fuelType) {
            return $query->where('fuel_type', $fuelType);
        }
        return $query;
    }

    public function getRequiresServiceAttribute()
    {
        $nextService = $this->getNextServiceDue();
        
        if (!$nextService) {
            return false;
        }

        return $nextService->isPast() || $nextService->diffInDays(now()) <= 30;
    }

    public function getNextServiceDue()
    {
        $lastOilChange = $this->serviceRecords()
            ->where('service_type', 'Oil Change')
            ->latest()
            ->first();

        if ($lastOilChange) {
            return $lastOilChange->service_date->addMonths(3);
        }

        return null;
    }

    public function requiresService()
    {
        $nextService = $this->getNextServiceDue();
        
        if (!$nextService) {
            return false;
        }

        return $nextService->isPast() || $nextService->diffInDays(now()) <= 30;
    }

    public function getTotalServiceCost()
    {
        return $this->serviceRecords()->sum('cost');
    }

    public function getLastService()
    {
        return $this->serviceRecords()->latest()->first();
    }
}
