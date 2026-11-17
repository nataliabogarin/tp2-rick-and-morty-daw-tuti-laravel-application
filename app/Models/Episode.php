<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class Episode extends Model
{
    protected $table = 'episode';
    protected $primaryKey = 'id_episode';

    protected $fillable = ['name', 'air_date', 'episode'];

    // Relación Muchos a Muchos con Personajes
    public function characters(): BelongsToMany
    {
        return $this->belongsToMany(
            Character::class,
            'characters_episode',
            'id_episode',
            'id_character',
            'id_episode',
            'id_character'
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
     * Método de ayuda para verificar si un usuario marcó este episodio como favorito.
     */
    public function isFavoritedBy(?User $user): bool
    {
        if (!$user) {
            return false;
        }

        return $this->favorites()->where('user_id', $user->id)->exists();
    }

}
