<?php

namespace App\Models\Mongo;

use MongoDB\Laravel\Eloquent\Model;

class WebsiteSetting extends Model
{
    protected $connection = 'mongodb';

    protected $table = 'website_settings';

    protected $fillable = [
        'key',
        'value',
    ];
}
