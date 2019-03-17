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
use Foundation\Generator\Events\EventGeneratedEvent;
use Foundation\Generator\Events\ListenerGeneratedEvent;
use Foundation\Generator\Events\ModelGeneratedEvent;
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
        $this->generator->addController('AController');

        $this->generator->addModel('AModel', true, true);

        $this->generator->addTest('AServiceTest', 'service');
        $this->generator->addTest('AHttpTest', 'http');
        $this->generator->addTest('AUnitTest', 'unit');

        $this->generator->addCommand('ACommand', 'command:dosomething');

        $this->generator->addEvent('AEvent');

        $this->generator->addListener('AListener', 'AEvent');

        $this->generator->addRequest('ARequest');

        $this->generator->addRoute();

        $this->generator->addComposer();

        $this->generator->addMiddleware('AMiddleware');

        $this->generator->addPolicy('APolicy');

        $this->generator->addFactory('AModel');


        $this->generator->generate();

        /* @var ControllerGeneratedEvent $event */
        $event = $this->getFirstDispatchedEvent(ControllerGeneratedEvent::class);
        $this->assertNotNull($event);
        $this->assertEquals("AController", $event->getClassName());

        /* @var ModelGeneratedEvent $event */
        $event = $this->getFirstDispatchedEvent(ModelGeneratedEvent::class);
        Event::assertDispatched(ModelGeneratedEvent::class, 1);
        $this->assertNotNull($event);
        $this->assertEquals("AModel", $event->getClassName());
        $this->assertTrue($event->isMongoModel());
        $this->assertTrue($event->includesMigration());


        /* @var TestGeneratedEvent[] $events */
        $events = $this->getDispatchedEvents(TestGeneratedEvent::class);
        $this->assertNotEmpty($events);

        $this->assertEquals($events[0]->getClassName(), "AServiceTest");
        $this->assertEquals($events[0]->getType(), "service");

        $this->assertEquals($events[1]->getClassName(), "AHttpTest");
        $this->assertEquals($events[1]->getType(), "http");

        $this->assertEquals($events[2]->getClassName(), "AUnitTest");
        $this->assertEquals($events[2]->getType(), "unit");

        Event::assertDispatched(CommandGeneratedEvent::class, 1);

        /* @var CommandGeneratedEvent $event */
        $event = $this->getFirstDispatchedEvent(CommandGeneratedEvent::class);
        $this->assertEquals("ACommand", $event->getClassName());
        $this->assertEquals("command:dosomething", $event->getConsoleCommand());
        $this->assertNotNull($event);

        /* @var EventGeneratedEvent $event */
        $event = $this->getFirstDispatchedEvent(EventGeneratedEvent::class);
        $this->assertEquals("AEvent", $event->getClassName());
        $this->assertNotNull($event);

        /* @var ListenerGeneratedEvent $event */
        $event = $this->getFirstDispatchedEvent(ListenerGeneratedEvent::class);
        $this->assertEquals("AListener", $event->getClassName());
        $this->assertNotNull($event);
    }
}
