<?php

namespace Modules\Script\Exceptions;

use Foundation\Exceptions\Exception;

class ScriptReviewNotFoundException extends Exception
{

    public function __construct()
    {
        parent::__construct("Could not find script review", 404, null);
    }
}
