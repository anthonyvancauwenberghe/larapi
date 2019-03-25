<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 24.03.19
 * Time: 20:00
 */

namespace Modules\Script\Guards;


use Foundation\Guard\Abstracts\Guard;
use Modules\Script\Entities\Script;
use Modules\Script\Entities\ScriptReview;
use Modules\Script\Exceptions\ScriptAlreadyReviewedException;

/**
 * Class ScriptAlreadyReviewedGuard
 * @package Modules\Script\Guards
 */
class ScriptAlreadyReviewedGuard extends Guard
{

    /**
     * The exception that will be thrown when the condition is met
     *
     * @var string
     */
    protected $exception = ScriptAlreadyReviewedException::class;


    /**
     * @var Script
     */
    protected $script;

    /**
     * ScriptAlreadyReviewedGuard constructor.
     * @param $script
     */
    public function __construct(Script $script)
    {
        $this->script = $script;
    }

    /**
     * The condition that needs to be satisfied in order to throw the exception.
     *
     * @return bool
     */
    public function condition(): bool
    {
        return $this->script->reviews()
            ->withTrashed()
            ->where(ScriptReview::REVIEWER_ID, get_authenticated_user_id())
            ->get()
            ->isNotEmpty();
    }

}
