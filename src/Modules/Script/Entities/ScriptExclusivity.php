<?php

namespace Modules\Script\Entities;

use Modules\Mongo\Abstracts\MongoModel as Model;
use Modules\Script\Attributes\ScriptExclusivityAttributes;
use Foundation\Traits\ModelFactory;

/**
 * Class ScriptExclusivity.
 *
 * @property string $id
 */
class ScriptExclusivity extends Model implements ScriptExclusivityAttributes
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
