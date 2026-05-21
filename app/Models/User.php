<?php

namespace App\Models;

<<<<<<< HEAD
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
=======
use App\Enums\UserRole;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
>>>>>>> ec6237d (Third Week of Assignment small changes)
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
<<<<<<< HEAD
    use HasApiTokens, HasFactory, HasProfilePhoto, Notifiable, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     */
=======
    use HasApiTokens;
    /** @use HasFactory<UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

>>>>>>> ec6237d (Third Week of Assignment small changes)
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

<<<<<<< HEAD
    /**
     * The attributes that should be hidden for serialization.
     */
=======
>>>>>>> ec6237d (Third Week of Assignment small changes)
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

<<<<<<< HEAD
    /**
     * The accessors to append to the model's array form.
     */
=======
>>>>>>> ec6237d (Third Week of Assignment small changes)
    protected $appends = [
        'profile_photo_url',
    ];

<<<<<<< HEAD
    /**
     * The attributes that should be cast.
     */
=======
>>>>>>> ec6237d (Third Week of Assignment small changes)
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
<<<<<<< HEAD
        ];
    }


    public function vehicles()
=======
            'role' => UserRole::class,
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === UserRole::Admin;
    }

    public function vehicles(): HasMany
>>>>>>> ec6237d (Third Week of Assignment small changes)
    {
        return $this->hasMany(Vehicle::class);
    }

<<<<<<< HEAD
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isUser()
    {
        return $this->role === 'user';
    }
}
=======
    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }
}
>>>>>>> ec6237d (Third Week of Assignment small changes)
