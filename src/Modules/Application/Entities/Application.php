<?php

namespace Modules\Application\Entities;

use Foundation\Contracts\Ownable;
use Foundation\Traits\ModelFactory;
use Foundation\Traits\OwnedByUser;
use Jenssegers\Mongodb\Eloquent\Builder;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;
use Modules\Application\Attributes\ApplicationAttributes;
use Modules\Machine\Entities\Machine;
use Modules\Mongo\Abstracts\MongoModel;
use Modules\User\Entities\User;

/**
 * Class Application.
 *
 * @property string $_id
 */
abstract class Application extends MongoModel implements Ownable, ApplicationAttributes
{
    use OwnedByUser, ModelFactory, SoftDeletes;

    /**
     * @var string
     */
    protected $collection = 'applications';

    /**
     * @var array
     */
    protected $guarded = [];

    protected $casts = [
        'active' => 'boolean',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('type', function (Builder $builder) {
            $builder->where('type', get_short_class_name(static::class));
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }
}
