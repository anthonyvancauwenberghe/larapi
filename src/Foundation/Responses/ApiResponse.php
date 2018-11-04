<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 29.10.18
 * Time: 16:25.
 */

namespace Foundation\Responses;

use Illuminate\Database\Eloquent\Collection;

class ApiResponse
{
    public static function deleted()
    {
        return \response()->noContent(204);
    }
}
