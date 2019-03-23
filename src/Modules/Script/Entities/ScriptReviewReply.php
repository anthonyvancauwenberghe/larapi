<?php

namespace Modules\Script\Entities;

use Modules\Mongo\Abstracts\MongoModel as Model;
use Modules\Script\Attributes\ScriptReviewReplyAttributes;
use Foundation\Traits\ModelFactory;
use Foundation\Contracts\Ownable;
use Foundation\Traits\OwnedByUser;

/**
 * Class ScriptReviewReply.
 *
 * @property string $id
 */
class ScriptReviewReply extends Model implements ScriptReviewReplyAttributes, Ownable
{
    use ModelFactory, OwnedByUser;

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
