<?php

namespace Modules\Synchronisation\Entities;

use Modules\Mongo\Abstracts\MongoModel;

class Task extends MongoModel
{
    protected $collection = 'tasks';

    protected $fillable = [

    ];

    protected $attributes = [

    ];

    protected $hidden = [

    ];

    protected $casts = [

    ];

    protected $dates = [
        'completed_at',
        'dispatched_at',
        'failed_at',
        'created_at',
        'updated_at'
    ];

    public $timestamps = true;

}
