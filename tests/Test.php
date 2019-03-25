<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 11.10.18
 * Time: 12:32.
 */

namespace Tests;

use Foundation\Abstracts\Tests\HttpTest;
use Modules\Script\Dtos\GrantUserExclusivityDto;

class Test extends HttpTest
{
    public function test()
    {
        $input = new GrantUserExclusivityDto([
            "user_id" => 5,
            'recurring_price' => 4.0,
            'base_price' => 2.0
        ]);

        $input->recurring_price = 5.0;

        $this->assertEquals(5.0,$input->recurring_price);
    }
}
