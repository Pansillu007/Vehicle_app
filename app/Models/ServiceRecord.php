<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
<<<<<<< HEAD
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ServiceRecord extends Model
{
    use HasFactory;

    protected static function newFactory()
    {
        return \Database\Factories\ServiceRecordFactory::new();
    }
=======
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceRecord extends Model
{
    use SoftDeletes;
>>>>>>> ec6237d (Third Week of Assignment small changes)

    protected $fillable = [
        'vehicle_id',
        'service_type',
        'description',
<<<<<<< HEAD
        'cost',
        'service_date',
        'service_provider',
    ];

    protected $casts = [
        'cost' => 'decimal:2',
        'service_date' => 'date',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('service_type', 'like', "%{$search}%")
            ->orWhere('description', 'like', "%{$search}%")
            ->orWhere('service_provider', 'like', "%{$search}%");
    }

    public function scopeFilterByDate($query, $date)
    {
        return $query->whereDate('service_date', $date);
    }
=======
        'service_date',
        'cost',
        'mileage',
        'service_provider',
    ];

    protected function casts(): array
    {
        return [
            'service_date' => 'date',
            'cost' => 'decimal:2',
        ];
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }
>>>>>>> ec6237d (Third Week of Assignment small changes)
}
