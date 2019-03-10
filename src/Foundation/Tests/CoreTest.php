<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 03.10.18
 * Time: 22:58.
 */

namespace Foundation\Tests;

use Foundation\Abstracts\Tests\TestCase;
use Foundation\Core\Larapi;
use Foundation\Core\Module;

class CoreTest extends TestCase
{
    public function testGetModules()
    {
        $modules = Larapi::getModules();
        foreach ($modules as $module) {
            $this->assertInstanceOf(Module::class, $module);
        }
    }

    public function testGetModuleNames()
    {
        $moduleNames = Larapi::getModuleNames();
        $this->assertContains('User', $moduleNames);
    }

    public function testUserNamespace()
    {
        $module = Larapi::getModule('user');
        $this->assertEquals('Modules\User', $module->getNamespace());
    }

    public function testAmountOfUserModels()
    {
        $module = Larapi::getModule('user');

        $this->assertEquals(1, $module->getModels()->amount());
    }
}
