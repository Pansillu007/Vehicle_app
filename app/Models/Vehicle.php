<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Vehicle extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'make',
        'model',
        'number_plate',
        'year',
        'color',
        'mileage',
        'fuel_type',
        'vin_number',
        'image_path',
        'next_service_due_date',
        'next_service_due_mileage',
    ];

    protected function casts(): array
    {
        return [
            'year' => 'integer',
            'mileage' => 'integer',
            'next_service_due_date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function serviceRecords(): HasMany
    {
        return $this->hasMany(ServiceRecord::class);
    }

    public function services(): HasMany
    {
        return $this->serviceRecords();
    }

    public function reminders(): HasMany
    {
        return $this->hasMany(Reminder::class);
    }

    public function fuelLogs(): HasMany
    {
        return $this->hasMany(FuelLog::class);
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
    }

    public function scopeSearch($query, ?string $search)
    {
        if (! $search) {
            return $query;
        }

        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('make', 'like', "%{$search}%")
                ->orWhere('model', 'like', "%{$search}%")
                ->orWhere('number_plate', 'like', "%{$search}%");
        });
    }

    public function scopeFilterByMake($query, ?string $make)
    {
        return $make ? $query->where('make', $make) : $query;
    }

    public function scopeFilterByYear($query, $year)
    {
        return $year ? $query->where('year', $year) : $query;
    }

    public function scopeFilterByFuelType($query, ?string $fuelType)
    {
        return $fuelType ? $query->where('fuel_type', $fuelType) : $query;
    }
}
