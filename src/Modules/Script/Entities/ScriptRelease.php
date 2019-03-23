<?php

namespace Modules\Script\Entities;

use Modules\Mongo\Abstracts\MongoModel as Model;
use Modules\Script\Attributes\ScriptReleaseAttributes;
use Foundation\Traits\ModelFactory;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

/**
 * Class ScriptRelease.
 *
 * @property string $id
 */
class ScriptRelease extends Model implements ScriptReleaseAttributes
{
    use ModelFactory;

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var array
     */
    protected $casts = [];

    protected $dates = [
        'created_at',
        'updated_at'
    ];
}
