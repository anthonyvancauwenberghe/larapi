<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 15.10.18
 * Time: 23:08
 */

namespace Foundation\Abstracts\Controller;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
