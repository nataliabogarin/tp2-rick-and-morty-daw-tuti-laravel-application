<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class, 'user_id');
    }

    public function customLists(): HasMany
    {
        return $this->hasMany(CustomList::class, 'user_id');
    }

    /**
     * Listas del usuario de un tipo ("character" o "episode") con la cantidad de elementos
     * y un campo "contiene" que indica si el elemento dado ya está en cada lista.
     */
    public function listsOfTypeWithItem(string $type, int $itemId)
    {
        return $this->customLists()
            ->where('type', $type)
            ->withCount('items')
            ->withExists(['items as contiene' => fn ($query) => $query->where('item_id', $itemId)])
            ->orderBy('name')
            ->get();
    }
}
