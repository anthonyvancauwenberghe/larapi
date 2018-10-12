<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 03.10.18
 * Time: 22:58.
 */

namespace Foundation\Tests;

use Foundation\Abstracts\Tests\CreatesApplication;
use Foundation\Abstracts\Tests\TestCase;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Modules\User\Entities\User;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class HelpersTest extends TestCase
{
    public function testClassImplementsHelper()
    {
        $this->assertFalse(classImplementsInterface(self::class, Filesystem::class));
        $this->assertTrue(classImplementsInterface(self::class, \PHPUnit\Framework\Test::class));
        $this->assertFalse(classImplementsInterface(new self(), Filesystem::class));
        $this->assertTrue(classImplementsInterface(new self(), \PHPUnit\Framework\Test::class));
    }

    public function testUnauthenticatedUserHelper()
    {
        $this->expectException(UnauthorizedHttpException::class);
        getAuthenticatedUser();
    }

    public function testAuthenticatedUserHelper()
    {
        $this->actingAs(factory(User::class)->make());
        $this->assertTrue(classImplementsInterface(getAuthenticatedUser(), Authenticatable::class));
    }

    public function testClassShortNameHelper()
    {
        $this->assertEquals('HelpersTest', getShortClassName(self::class));
    }

    public function testRandomArrayElementHelper()
    {
        $array = [
            'test',
            'x',
            'blabla',
            'hello',
            'hey',
        ];

        $randomArrayElement = getRandomArrayElement($array);

        $this->assertContains($randomArrayElement, $array);
    }

    public function testClassUsesTraitHelper()
    {
        $this->assertTrue(classUsesTrait(self::class, CreatesApplication::class));
        $this->assertFalse(classUsesTrait(self::class, Authorizable::class));
    }
}
