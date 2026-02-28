<?php

namespace App\Models\Mongo;

use MongoDB\Laravel\Eloquent\Model;

class UserAccount extends Model
{
    protected $connection = 'mongodb';

    protected $table = 'users';

    protected $fillable = [
        'legacy_user_id',
        'name',
        'email',
        'username',
        'profile_photo_path',
        'password',
        'roles',
        'permissions',
        'email_verified_at',
        'remember_token',
    ];

    protected $casts = [
        'roles' => 'array',
        'permissions' => 'array',
        'email_verified_at' => 'datetime',
    ];
}
