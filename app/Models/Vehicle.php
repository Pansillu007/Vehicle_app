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
            ->orWhere('model', 'like', "%{$search}%")
            ->orWhere('number_plate', 'like', "%{$search}%");
    }
}
