<?php

namespace App\Models\Mongo;

use MongoDB\Laravel\Eloquent\Model;

class Permission extends Model
{
    protected $connection = 'mongodb';

    protected $table = 'permissions';

    protected $fillable = [
        'legacy_permission_id',
        'name',
        'guard_name',
    ];
}
