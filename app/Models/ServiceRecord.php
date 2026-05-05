<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ServiceRecord extends Model
{
    use HasFactory;

    protected static function newFactory()
    {
        return \Database\Factories\ServiceRecordFactory::new();
    }

    protected $fillable = [
        'vehicle_id',
        'service_type',
        'description',
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
}
