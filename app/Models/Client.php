<?php

namespace App\Models;

use App\Events\ClientRegistered;
use App\Traits\InteractsWithVerification;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Client extends Authenticatable implements JWTSubject
{
    use InteractsWithVerification, Notifiable;
    
    protected $table = 'clients';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'birthdate',
        'summary',
        "email_verified_at",
        "phone_verified_at",
        "verified_meta",
        "password",
    ];

    protected $hidden = [
        "password",
        "verified_meta",
    ];

    protected function casts()
    {
        return [
            'birthdate' => 'date',
            "password" => "hashed",
            "email_verified_at" => "datetime",
            "phone_verified_at" => "datetime",
            "verified_meta" => "encrypted:array",
        ];
    }

    protected function name(): Attribute
    {
        return Attribute::make(
            set: function ($value) {
                return Str::of($value)->trim()->title();
            }
        );
    }

    protected static function booted()
    {
        static::created(function (Client $model) {
            event(new ClientRegistered($model));
        });
    }

    public function hasVerified()
    {
        $emailValid = !$this->email || $this->hasVerifiedBy('email');
        
        $phoneValid = !$this->phone || $this->hasVerifiedBy('phone');
        
        $atLeastOneVerified = $this->hasVerifiedBy('email') || $this->hasVerifiedBy('phone');
        
        return $emailValid && $phoneValid && $atLeastOneVerified;
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
