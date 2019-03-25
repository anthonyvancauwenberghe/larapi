<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 21.02.19
 * Time: 18:16
 */

namespace Modules\Client\Entities;


use Foundation\Contracts\Ownable;
use Foundation\Traits\ModelFactory;
use Foundation\Traits\OwnedByUser;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;
use Modules\Application\Entities\Application;
use Modules\Machine\Entities\Machine;
use Modules\Mongo\Abstracts\MongoModel;
use Modules\User\Entities\User;

class Client extends MongoModel implements Ownable
{
    use OwnedByUser, ModelFactory, SoftDeletes;

    /**
     * @var string
     */
    protected $collection = 'clients';

    /**
     * @var array
     */
    protected $guarded = [];

    protected $casts = [
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'last_heartbeat',
        'started_at',
        'closed_at'
    ];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }

}