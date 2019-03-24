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
use Symfony\Component\HttpFoundation\File\Exception\UnexpectedTypeException;

class GuardsResolver extends Resolver
{
    protected $guard;

    /**
     * GuardsResolver constructor.
     * @param $guard
     */
    public function __construct($guard)
    {
        $this->guard = $guard;
    }

    public function resolve()
    {
        $guards = [];
        if (!is_array($this->guard)) {
            $guards[] = $this->guard;
        }
        else
        $guards = $this->guard;

        foreach ($guards as $guard) {
            if (!($guard instanceof GuardContract))
                throw new UnexpectedTypeException($guard, GuardContract::class);
        }
        return $guards;
    }


}
