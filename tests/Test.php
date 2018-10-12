<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 11.10.18
 * Time: 12:32
 */

namespace Tests;

use Foundation\Abstracts\Tests\TestCase;
use Modules\User\Jobs\AJob;

class Test extends TestCase
{
    public function test()
    {
        $this->expectsJobs(AJob::class);
        dispatch(new AJob());
        $this->assertTrue(true);
    }
}
