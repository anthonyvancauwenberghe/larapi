<?php

namespace Foundation\Tests;

use Foundation\Core\Larapi;
use Foundation\Generator\Events\FileGeneratedEvent;
use Foundation\Generator\Managers\GeneratorManager;
use Foundation\Traits\DisableRefreshDatabase;
use \Illuminate\Support\Facades\Event;

/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 10.03.19
 * Time: 18:35
 */
class GeneratorTest extends \Foundation\Abstracts\Tests\TestCase
{
    use DisableRefreshDatabase;

    public function testCreateSqlMigration()
    {
        Event::fake();
        GeneratorManager::createMigration("User", "CreateUserTable", 'users', false);

        $module = Larapi::getModule("User");
        $expectedDirectoryPath = $module->getMigrations()->getPath();
        $expectedStubName = "migration.stub";
        $expectedStubOptions = [
            'CLASS' => 'CreateUserTable',
            'NAMESPACE' => $module->getMigrations()->getNamespace(),
            'TABLE' => 'users'
        ];

        Event::assertDispatched(FileGeneratedEvent::class, function (FileGeneratedEvent $event) use ($expectedDirectoryPath, $expectedStubName, $expectedStubOptions) {
            $this->assertStringContainsString($expectedDirectoryPath, $event->getFilePath());
            $this->assertEquals($expectedStubName, $event->getStubName());
            $this->assertEquals($expectedStubOptions, $event->getStubOptions());
            return true;
        });
    }

    public function testCreateMongoMigration()
    {
        Event::fake();
        GeneratorManager::createMigration("User", "CreateUserCollection", 'users', true);
        Event::assertDispatched(FileGeneratedEvent::class, function (FileGeneratedEvent $event) {
            $this->assertEquals("migration-mongo.stub", $event->getStubName());
            return true;
        });
    }

    public function testCreateFactory()
    {
        $moduleName = "User";
        Event::fake();
        GeneratorManager::createFactory($moduleName, "User");

        $expectedFileName = Larapi::getModule($moduleName)->getFactories()->getPath() . '/UserFactory.php';
        $expectedStubName = "factory.stub";
        $expectedStubOptions = [
            'CLASS' => 'UserFactory',
            'MODEL' => 'User',
            'MODEL_NAMESPACE' => 'Modules\User\Entities\User'
        ];

        Event::assertDispatched(FileGeneratedEvent::class, function (FileGeneratedEvent $event) use ($expectedFileName, $expectedStubName, $expectedStubOptions) {
            $this->assertEquals($expectedFileName, $event->getFilePath());
            $this->assertEquals($expectedStubName, $event->getStubName());
            $this->assertEquals($expectedStubOptions, $event->getStubOptions());
            return true;
        });
    }

    public function testCreateController()
    {
        $moduleName = "User";
        Event::fake();
        GeneratorManager::createController($moduleName, "UserController");

        $expectedFileName = Larapi::getModule($moduleName)->getControllers()->getPath() . '/UserController.php';
        $expectedStubName = "controller.stub";
        $expectedStubOptions = [
            'CLASS' => 'UserController',
            'NAMESPACE' => 'Modules\User\Http\Controllers'
        ];

        Event::assertDispatched(FileGeneratedEvent::class, function (FileGeneratedEvent $event) use ($expectedFileName, $expectedStubName, $expectedStubOptions) {
            $this->assertEquals($expectedFileName, $event->getFilePath());
            $this->assertEquals($expectedStubName, $event->getStubName());
            $this->assertEquals($expectedStubOptions, $event->getStubOptions());
            return true;
        });
    }

    public function testCreateListener()
    {
        $moduleName = "User";
        Event::fake();
        GeneratorManager::createListener($moduleName, "SendWelcomeMail", "UserRegisteredEvent");

        $expectedFileName = Larapi::getModule($moduleName)->getListeners()->getPath() . '/SendWelcomeMail.php';
        $expectedStubName = "listener.stub";
        $expectedStubOptions = [
            'CLASS' => 'SendWelcomeMail',
            'NAMESPACE' => 'Modules\User\Listeners',
            'EVENTNAME' => 'Modules\User\Events\UserRegisteredEvent',
            'SHORTEVENTNAME' => 'UserRegisteredEvent',
        ];

        Event::assertDispatched(FileGeneratedEvent::class, function (FileGeneratedEvent $event) use ($expectedFileName, $expectedStubName, $expectedStubOptions) {
            $this->assertEquals($expectedFileName, $event->getFilePath());
            $this->assertEquals($expectedStubName, $event->getStubName());
            $this->assertEquals($expectedStubOptions, $event->getStubOptions());
            return true;
        });
    }

    public function testCreateQueuedListener()
    {
        Event::fake();
        GeneratorManager::createListener("User", "SendWelcomeMail", "UserRegisteredEvent", true);
        Event::assertDispatched(FileGeneratedEvent::class, function (FileGeneratedEvent $event) {
            $this->assertEquals("listener-queued.stub", $event->getStubName());
            return true;
        });
    }

    public function testCreateJob()
    {
        $moduleName = "User";
        $fileName = "RandomUserJob";
        Event::fake();
        GeneratorManager::createJob($moduleName, $fileName, false);

        $expectedFileName = Larapi::getModule($moduleName)->getJobs()->getPath() . "/$fileName.php";
        $expectedStubName = "job-queued.stub";
        $expectedStubOptions = [
            'NAMESPACE' => 'Modules\User\Jobs',
            'CLASS' => 'RandomUserJob'
        ];

        Event::assertDispatched(FileGeneratedEvent::class, function (FileGeneratedEvent $event) use ($expectedFileName, $expectedStubName, $expectedStubOptions) {
            $this->assertEquals($expectedFileName, $event->getFilePath());
            $this->assertEquals($expectedStubName, $event->getStubName());
            $this->assertEquals($expectedStubOptions, $event->getStubOptions());
            return true;
        });
    }

    public function testCreateSynchronousJob()
    {
        Event::fake();
        GeneratorManager::createJob("User", "AJob", true);
        Event::assertDispatched(FileGeneratedEvent::class, function (FileGeneratedEvent $event) {
            $this->assertEquals("job.stub", $event->getStubName());
            return true;
        });
    }

    public function testCreateCommand()
    {
        $moduleName = "User";
        $fileName = "RandomCommand";
        $commandName = "user:dosomethingrandom";

        Event::fake();
        GeneratorManager::createCommand($moduleName, $fileName, $commandName);

        $expectedFileName = Larapi::getModule($moduleName)->getCommands()->getPath() . "/$fileName.php";
        $expectedStubName = "command.stub";
        $expectedStubOptions = [
            'NAMESPACE' => 'Modules\User\Console',
            'CLASS' => 'RandomCommand',
            'COMMAND_NAME' => $commandName
        ];

        Event::assertDispatched(FileGeneratedEvent::class, function (FileGeneratedEvent $event) use ($expectedFileName, $expectedStubName, $expectedStubOptions) {
            $this->assertEquals($expectedFileName, $event->getFilePath());
            $this->assertEquals($expectedStubName, $event->getStubName());
            $this->assertEquals($expectedStubOptions, $event->getStubOptions());
            return true;
        });
    }

    public function testCreateMiddleware()
    {
        $moduleName = "User";
        $fileName = "RandomMiddleware";

        Event::fake();
        GeneratorManager::createMiddleware($moduleName, $fileName);

        $expectedFileName = Larapi::getModule($moduleName)->getMiddleWare()->getPath() . "/$fileName.php";
        $expectedStubName = "middleware.stub";
        $expectedStubOptions = [
            'CLASS' => 'RandomMiddleware',
            'NAMESPACE' => 'Modules\User\Http\Middleware'
        ];

        Event::assertDispatched(FileGeneratedEvent::class, function (FileGeneratedEvent $event) use ($expectedFileName, $expectedStubName, $expectedStubOptions) {
            $this->assertEquals($expectedFileName, $event->getFilePath());
            $this->assertEquals($expectedStubName, $event->getStubName());
            $this->assertEquals($expectedStubOptions, $event->getStubOptions());
            return true;
        });
    }

    public function testCreateProvider()
    {
        $moduleName = "User";
        $fileName = "RandomServiceProvider";

        Event::fake();
        GeneratorManager::createServiceProvider($moduleName, $fileName);

        $expectedFileName = Larapi::getModule($moduleName)->getServiceProviders()->getPath() . "/$fileName.php";
        $expectedStubName = "provider.stub";
        $expectedStubOptions = [
            'NAMESPACE' => 'Modules\User\Providers',
            'CLASS' => 'RandomServiceProvider'
        ];

        Event::assertDispatched(FileGeneratedEvent::class, function (FileGeneratedEvent $event) use ($expectedFileName, $expectedStubName, $expectedStubOptions) {
            $this->assertEquals($expectedFileName, $event->getFilePath());
            $this->assertEquals($expectedStubName, $event->getStubName());
            $this->assertEquals($expectedStubOptions, $event->getStubOptions());
            return true;
        });
    }

    public function testCreateNotification()
    {
        $moduleName = "User";
        $fileName = "RandomNotification";

        Event::fake();
        GeneratorManager::createNotification($moduleName, $fileName);

        $expectedFileName = Larapi::getModule($moduleName)->getNotifications()->getPath() . "/$fileName.php";
        $expectedStubName = "notification.stub";
        $expectedStubOptions = [
            'NAMESPACE' => 'Modules\User\Notifications',
            'CLASS' => 'RandomNotification'
        ];

        Event::assertDispatched(FileGeneratedEvent::class, function (FileGeneratedEvent $event) use ($expectedFileName, $expectedStubName, $expectedStubOptions) {
            $this->assertEquals($expectedFileName, $event->getFilePath());
            $this->assertEquals($expectedStubName, $event->getStubName());
            $this->assertEquals($expectedStubOptions, $event->getStubOptions());
            return true;
        });
    }
}
