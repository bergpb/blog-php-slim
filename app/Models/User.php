<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'confirmation_key',
        'confirmation_expires'
    ];

    public function permissions()
    {
        return $this->hasOne('App\Models\UserPermission', 'user_id');
    }
}