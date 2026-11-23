<?php

namespace App\Models;

use Database\Factories\CharacterFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Character extends Model
{
    /** @use HasFactory<CharacterFactory> */
    use HasFactory;

    protected $table = 'characters';

    protected $primaryKey = 'id_character';

    public $incrementing = false;

    public $timestamps = false;

    protected $keyType = 'int';

    protected $fillable = [
        'id_character',
        'name',
        'status',
        'species',
        'type',
        'gender',
        'image',
        'id_origin_location',
        'id_current_location',
    ];

    public function origin(): BelongsTo
    {
        return $this->belongsTo(
            Location::class,
            'id_origin_location',
            'id_location'
        );
    }

    public function currentLocation(): BelongsTo
    {
        return $this->belongsTo(
            Location::class,
            'id_current_location',
            'id_location'
        );
    }

    public function episodes(): BelongsToMany
    {
        return $this->belongsToMany(
            Episode::class,
            'characters_episode',
            'id_character',
            'id_episode',
            'id_character',
            'id_episode'
        );
    }
}
