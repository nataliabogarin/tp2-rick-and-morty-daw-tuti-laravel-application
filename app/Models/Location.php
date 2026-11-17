<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
    protected $table = 'location';
    protected $primaryKey = 'id_location';
    protected $fillable = [ 'name', 'type', 'dimension'];

    public function originCharacters(): HasMany
    {
        return $this->hasMany(Character::class, 'id_origin_location', 'id_location');
    }

    public function currentCharacters(): HasMany
    {
        return $this->hasMany(Character::class, 'id_current_location', 'id_location');
    }

        /**
     * Relación polimórfica con los favoritos.
     */
    public function favorites(): MorphMany
    {
        return $this->morphMany(Favorite::class, 'favorable');
    }

    /**
     * Método de ayuda para verificar si un usuario marcó esta locacion como favorito.
     */
    public function isFavoritedBy(?User $user): bool
    {
        if (!$user) {
            return false;
        }

        return $this->favorites()->where('user_id', $user->id)->exists();
    }


}
