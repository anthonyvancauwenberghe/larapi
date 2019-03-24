<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 24.03.19
 * Time: 19:24
 */

namespace Foundation\Guard\Dispatcher;


use Foundation\Exceptions\Exception;
use Foundation\Guard\Contracts\GuardContract;
use Foundation\Guard\Exceptions\CouldNotResolveStringException;
use Foundation\Guard\Resolvers\ExceptionResolver;
use Foundation\Guard\Resolvers\GuardsResolver;
use Symfony\Component\HttpFoundation\File\Exception\UnexpectedTypeException;
use Throwable;

class GuardDispatcher
{

    /**
     * @var GuardContract[]
     */
    protected $guards = [];

    /**
     * @var Throwable|null
     */
    protected $exception;

    /**
     * GuardDispatcher constructor.
     * @param $guards
     * @param $exception
     */
    public function __construct($guards, $exception = null)
    {
        $this->guards = (new GuardsResolver($guards))->resolve();
        $this->exception = isset($exception) ? (new ExceptionResolver($exception))->resolve() : null;
    }

    public function dispatch()
    {
        foreach ($this->guards as $guard) {
            if ($guard->condition()) {
                if (isset($this->exception))
                    throw $this->exception;
                else
                    throw $guard->exception();
            }
        }
    }
}
