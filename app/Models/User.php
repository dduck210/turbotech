<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Maps onto the existing `user` table from the legacy app (not renamed —
 * schema renames are deferred to the final cutover phase rather than
 * applied while the legacy app is still live).
 *
 * `role` 0 = customer, 1 = admin — a single `users` table + role column,
 * not a separate admins table.
 */
class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'user';

    protected $primaryKey = 'id_user';

    public $timestamps = false;

    protected $fillable = [
        'user_name',
        'full_name',
        'email_user',
        'password',
        'sex',
        'address',
        'phone_user',
        'img_user',
        'role',
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'role' => 'integer',
            'sex' => 'integer',
            'register_date' => 'date',
            'last_login' => 'date',
        ];
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'id_user', 'id_user');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'id_user', 'id_user');
    }

    public function scopeAdmins($query)
    {
        return $query->where('role', 1);
    }
}