<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Character extends Model
{
    protected $table= 'Characters';
    protected $primaryKey= 'id_character';
    protected $fillable= ['name', 'status', 'species', 'type', 'gender', 'image', 'id_origin_location', 'id_current_location'];

    // Relación de origen (Location)
    public function originLocation(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'id_origin_location', 'id_location');
    }   
    // Relación de ubicación actual (Location)
    public function currentLocation(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'id_current_location', 'id_location');
    }

    public function episode(): BelongsToMany
    {
        return $this->belongsToMany(Episode::class, 'characters_episode', 'id_character', 'id_episode','id_character', 'id_episode'); 
    }

    /**
     * Relación polimórfica con los favoritos.
     */
    public function favorites(): MorphMany
    {
        return $this->morphMany(Favorite::class, 'favorable');
    }

    /**
     * Método de ayuda para verificar si un usuario marcó este personaje como favorito.
     */
    public function isFavoritedBy(?User $user): bool
    {
        if (!$user) {
            return false;
        }

        return $this->favorites()->where('user_id', $user->id)->exists();
    }



}
