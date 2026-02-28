<?php

namespace App\Models\Mongo;

use MongoDB\Laravel\Eloquent\Model;

class Role extends Model
{
    protected $connection = 'mongodb';

    protected $table = 'roles';

    protected $fillable = [
        'legacy_role_id',
        'name',
        'guard_name',
        'permissions',
    ];

    protected $casts = [
        'permissions' => 'array',
    ];
}
