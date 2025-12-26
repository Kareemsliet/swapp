<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ClientProvider extends Model
{
    protected $table = 'client_providers';

    protected $fillable = [
        'client_id',
        'provider',
        'provider_id',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class,"client_id");
    }

    public function scopeByProvider(Builder $query, string $provider)
    {
        return $query->where('provider', $provider);
    }

    public function scopeByProviderId(Builder $query, string $providerId)
    {
        return $query->where('provider_id', $providerId);
    }
}
