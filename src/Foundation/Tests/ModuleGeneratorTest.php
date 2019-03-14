<?php
/**
 * Created by PhpStorm.
 * User: tony
 * Date: 12.03.19
 * Time: 16:39
 */

namespace Foundation\Tests;

use Foundation\Generator\Events\CommandGeneratedEvent;
use Foundation\Generator\Events\ControllerGeneratedEvent;
use Foundation\Generator\Events\TestGeneratedEvent;
use Foundation\Generator\Generators\ModuleGenerator;
use Foundation\Traits\DisableRefreshDatabase;
use Foundation\Traits\DispatchedEvents;
use Illuminate\Support\Facades\Event;

/**
 * Class ModuleGeneratorTest
 * @package Foundation\Tests
 */
class ModuleGeneratorTest extends \Foundation\Abstracts\Tests\TestCase
{
    use DisableRefreshDatabase, DispatchedEvents;


    /**
     * @var ModuleGenerator
     */
    protected $generator;

    /**
     * @throws \Nwidart\Modules\Exceptions\FileAlreadyExistException
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->generator = new ModuleGenerator("ARandomTestModule");

        /* Do not remove this line. It prevents the listener that generates the file from executing */
        Event::fake();
    }


    /**
     *
     */
    public function testPiping()
    {
        $this->generator->addController('TestController');
        $this->generator->addTest('ServiceTest', 'service');
        $pipeline = $this->generator->getPipeline();

        $this->assertIsArray($pipeline);
        $this->assertEquals('Controller', $pipeline[0]['name']);
    }

    public function testModuleGeneration()
    {


        $this->generator->addController($controller = 'AController');

        $this->generator->addTest($serviceTest = 'AServiceTest', $serviceType ='service');
        $this->generator->addTest($httpTest = 'AHttpTest', $httpType ='http');
        $this->generator->addTest($unitTest = 'AUnitTest', $unitType ='unit');

        $this->generator->addCommand($command = 'ACommand', $commandName = 'command:dosomething');


        $this->generator->generate();

        /* @var ControllerGeneratedEvent $event */
        $event = $this->getFirstDispatchedEvent(ControllerGeneratedEvent::class);
        $this->assertNotNull($event);
        $this->assertEquals($controller, $event->getClassName());


        Event::assertDispatched(TestGeneratedEvent::class, 3);

        /* @var TestGeneratedEvent[] $events */
        $events = $this->getDispatchedEvents(TestGeneratedEvent::class);
        $this->assertNotEmpty($events);

        $this->assertEquals($events[0]->getClassName(),$serviceTest);
        $this->assertEquals($events[0]->getType(),$serviceType);

        $this->assertEquals($events[1]->getClassName(),$httpTest);
        $this->assertEquals($events[1]->getType(),$httpType);

        $this->assertEquals($events[2]->getClassName(),$unitTest);
        $this->assertEquals($events[2]->getType(),$unitType);

        Event::assertDispatched(CommandGeneratedEvent::class, 1);

        /* @var CommandGeneratedEvent $event */
        $event = $this->getFirstDispatchedEvent(CommandGeneratedEvent::class);
        $this->assertEquals($commandName, $event->getConsoleCommand());
        $this->assertNotNull($event);


    }
}
