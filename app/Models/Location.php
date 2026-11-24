<?php

namespace App\Models;

use Database\Factories\LocationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
    /** @use HasFactory<LocationFactory> */
    use HasFactory;

    protected $table = 'location';

    protected $primaryKey = 'id_location';

    public $incrementing = false;

    public $timestamps = false;

    protected $keyType = 'int';

    protected $fillable = [
        'id_location',
        'name',
        'type',
        'dimension',
    ];

    // Relación con los personajes que tienen esta locación como origen
    public function originCharacters(): HasMany
    {
        return $this->hasMany(
            Character::class,
            'id_origin_location',
            'id_location'
        );
    }

    // Relación con los personajes ubicados actualmente en esta locación
    public function currentCharacters(): HasMany
    {
        return $this->hasMany(
            Character::class,
            'id_current_location',
            'id_location'
        );
    }

    /**
     * Relación polimórfica con los favoritos.
     */
    public function favorites(): MorphMany
    {
        return $this->morphMany(Favorite::class, 'favorable');
    }

    /**
     * Método de ayuda para verificar si un usuario
     * marcó esta locación como favorita.
     */
    public function isFavoritedBy(?User $user): bool
    {
        if (!$user) {
            return false;
        }

        return $this->favorites()
            ->where('user_id', $user->id)
            ->exists();
    }
}
