<?php

namespace Modules\Script\Exceptions;

use Foundation\Exceptions\Exception;

class UserDoesNotHaveExclusivityException extends Exception
{

    public function __construct()
    {
        parent::__construct("", 0, null);
    }

}
