<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 10.10.18
 * Time: 02:18.
 */

namespace Foundation\Controllers;

use Illuminate\Routing\Controller;

class FoundationController extends Controller
{
    public function main()
    {
        return response('welcome to astral');
    }

    public function api()
    {
        return response('Astral api');
    }

    public function authorized()
    {
        return response('authorized');
    }
}
