<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 24.02.19
 * Time: 19:11.
 */

namespace Modules\Synchronisation\Synchronisers;

class StateSynchroniser
{
    protected $initialState;
    protected $finalState;

    /**
     * StateSynchroniser constructor.
     * @param string $initialState
     * @param string $finalState
     */
    public function __construct(string $initialState, string $finalState)
    {
        $this->initialState = $initialState;
        $this->finalState = $finalState;
    }
}
