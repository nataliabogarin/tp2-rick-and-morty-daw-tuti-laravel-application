<?php

namespace App\Models;

use Database\Factories\EpisodeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Episode extends Model
{
    /** @use HasFactory<EpisodeFactory> */
    use HasFactory;

    protected $table = 'episode';

    protected $primaryKey = 'id_episode';

    public $incrementing = false;

    public $timestamps = false;

    protected $keyType = 'int';

    protected $fillable = [
        'id_episode',
        'name',
        'air_date',
        'episode',
    ];

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
}
