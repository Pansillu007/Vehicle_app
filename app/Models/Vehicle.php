<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
<<<<<<< HEAD
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vehicle extends Model
{
    use HasFactory;

    protected static function newFactory()
    {
        return \Database\Factories\VehicleFactory::new();
    }
=======
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Vehicle extends Model
{
    use SoftDeletes;
>>>>>>> ec6237d (Third Week of Assignment small changes)

    protected $fillable = [
        'user_id',
        'name',
        'make',
        'model',
<<<<<<< HEAD
        'year',
        'number_plate',
=======
        'number_plate',
        'year',
>>>>>>> ec6237d (Third Week of Assignment small changes)
        'color',
        'mileage',
        'fuel_type',
        'vin_number',
<<<<<<< HEAD
    ];

    protected $casts = [
        'year' => 'integer',
        'mileage' => 'decimal:2',
    ];

    public function user()
=======
        'image_path',
        'next_service_due_date',
        'next_service_due_mileage',
    ];

    protected function casts(): array
    {
        return [
            'next_service_due_date' => 'date',
        ];
    }

    public function user(): BelongsTo
>>>>>>> ec6237d (Third Week of Assignment small changes)
    {
        return $this->belongsTo(User::class);
    }

<<<<<<< HEAD
    public function serviceRecords()
=======
    public function serviceRecords(): HasMany
>>>>>>> ec6237d (Third Week of Assignment small changes)
    {
        return $this->hasMany(ServiceRecord::class);
    }

<<<<<<< HEAD
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
=======
    public function services(): HasMany
    {
        return $this->serviceRecords();
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->image_path && Storage::disk('public')->exists($this->image_path)) {
            return Storage::disk('public')->url($this->image_path);
        }

        return asset('images/vehicle-placeholder.svg');
    }

    public function totalMaintenanceCost(): float
    {
        return (float) $this->serviceRecords()->sum('cost');
>>>>>>> ec6237d (Third Week of Assignment small changes)
    }
}
