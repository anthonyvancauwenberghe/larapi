<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 24.03.19
 * Time: 20:00
 */

namespace Modules\Script\Guards;


use Foundation\Guard\Abstracts\Guard;
use Modules\Script\Entities\ScriptReview;
use Modules\Script\Exceptions\ScriptAlreadyReviewedException;

class ScriptAlreadyReviewedGuard extends Guard
{
    protected $exception = ScriptAlreadyReviewedException::class;

    protected $script;

    /**
     * ScriptAlreadyReviewedGuard constructor.
     * @param $script
     */
    public function __construct($script)
    {
        $this->script = $script;
    }

    public function condition(): bool
    {
        return $this->script->reviews()
            ->withTrashed()
            ->where(ScriptReview::REVIEWER_ID, get_authenticated_user_id())
            ->get()
            ->isNotEmpty();
    }


}
