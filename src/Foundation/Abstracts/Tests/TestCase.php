<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 09.10.18
 * Time: 21:56.
 */

namespace Foundation\Abstracts\Tests;

use Tests\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected function tearDown()
    {
        parent::tearDown();
        // \Cache::clear();
    }
}
