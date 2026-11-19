<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomList extends Model
{
    protected $fillable = ['user_id', 'name', 'type'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(CustomListItem::class);
    }

    // Devuelve los modelos Character o Episode reales que pertenecen a esta lista
    public function getResolvedItemsAttribute()
    {
        $ids = $this->items->pluck('item_id');

        if ($this->type === 'character') {
            return Character::whereIn('id_character', $ids)->get();
        }

        return Episode::whereIn('id_episode', $ids)->get();
    }
}