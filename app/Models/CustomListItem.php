<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomListItem extends Model
{
    protected $fillable = ['custom_list_id', 'item_id'];

    public function customList()
    {
        return $this->belongsTo(CustomList::class);
    }
}