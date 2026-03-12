<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class CustomPage extends Model
{
    protected $connection = 'mongodb';

    protected $table = 'custom_pages';

    protected $fillable = [
        'subdomain',
        'title',
        'html_content',
        'status',
        'created_by',
    ];

    protected static function boot()
    {
        parent::boot();

        try {
            $collection = (new static)->getConnection()->getCollection((new static)->getTable());
            $collection->createIndex(['subdomain' => 1], ['unique' => true]);
        } catch (\Exception $e) {
            // Index might already exist
        }
    }
}
