<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 24.03.19
 * Time: 19:41
 */

namespace Foundation\Guard\Resolvers;


use Foundation\Guard\Abstracts\Resolver;
use Foundation\Guard\Contracts\GuardContract;
use Foundation\Guard\Exceptions\CouldNotResolveStringException;
use Symfony\Component\HttpFoundation\File\Exception\UnexpectedTypeException;

class ExceptionResolver extends Resolver
{
    protected $exception;

    /**
     * GuardsResolver constructor.
     * @param $exception
     */
    public function __construct($exception)
    {
        $this->exception = $exception;
    }

    public function resolve()
    {
        if (is_string($this->exception) || !($this->exception instanceof \Throwable)) {
            try {
                $exception = new $this->exception;
            } catch (\Exception $e) {
                throw new CouldNotResolveStringException("Could not resolve the string to an exception. It probably requires additional arguments. try to pass it as an object to the dispatcher");
            }
        }
        return $exception;
    }


}
