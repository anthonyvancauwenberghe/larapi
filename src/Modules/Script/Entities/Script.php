<?php

namespace Modules\Script\Entities;

use Modules\Mongo\Abstracts\MongoModel as Model;
use Modules\Script\Policies\ScriptPolicy;
use Modules\Script\Attributes\ScriptAttributes;
use Foundation\Traits\ModelFactory;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;
use Foundation\Contracts\Ownable;
use Foundation\Traits\OwnedByUser;

/**
 * Class Script.
 *
 * @property string $id
 */
class Script extends Model implements ScriptAttributes, Ownable
{
    use ModelFactory, SoftDeletes, OwnedByUser;

    protected $policies = [
        ScriptPolicy::class
    ];

    protected $observers = [

    ];

    /**
     * @var string
     */
    protected $collection = 'scripts';

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
        'updated_at',
        'deleted_at',
    ];

    public function releases()
    {
        return $this->embedsMany(ScriptRelease::class);
    }

    public function reviews()
    {
        return $this->hasMany(ScriptReview::class);
    }

    public function exclusivity()
    {
        return $this->embedsMany(ScriptExclusivity::class);
    }
}
