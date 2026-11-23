<?php

namespace App\Models;

use Database\Factories\LocationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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

    public function originCharacters(): HasMany
    {
        return $this->hasMany(
            Character::class,
            'id_origin_location',
            'id_location'
        );
    }

    public function currentCharacters(): HasMany
    {
        return $this->hasMany(
            Character::class,
            'id_current_location',
            'id_location'
        );
    }
}
