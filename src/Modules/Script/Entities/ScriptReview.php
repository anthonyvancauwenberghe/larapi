<?php

namespace Modules\Script\Entities;

use Modules\Mongo\Abstracts\MongoModel as Model;
use Modules\Script\Attributes\ScriptReviewAttributes;
use Foundation\Traits\ModelFactory;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;
use Foundation\Contracts\Ownable;
use Foundation\Traits\OwnedByUser;
use Modules\User\Entities\User;

/**
 * Class ScriptReview.
 *
 * @property string $id
 */
class ScriptReview extends Model implements ScriptReviewAttributes, Ownable
{
    use ModelFactory, SoftDeletes, OwnedByUser;

    /**
     * @var string
     */
    protected $collection = 'script_reviews';

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

    public function reply(){
        return $this->embedsOne(ScriptReviewReply::class);
    }

    public function script(){
        return $this->belongsTo(Script::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
