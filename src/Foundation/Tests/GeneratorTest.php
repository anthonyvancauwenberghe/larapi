<?php

namespace Foundation\Tests;

use Foundation\Abstracts\Tests\TestCase;
use Foundation\Core\Larapi;
use Foundation\Generator\Commands\MigrationMakeCommand;
use Foundation\Generator\Commands\ModelMakeCommand;
use Foundation\Generator\Commands\PolicyMakeCommand;
use Foundation\Generator\Commands\RequestMakeCommand;
use Foundation\Generator\Commands\RuleMakeCommand;
use Foundation\Generator\Commands\SeederMakeCommand;
use Foundation\Generator\Commands\TestMakeCommand;
use Foundation\Generator\Commands\TransformerMakeCommand;
use Foundation\Generator\Events\FileGeneratedEvent;
use Foundation\Generator\Managers\GeneratorManager;
use Foundation\Generator\Traits\DispatchedGeneratorEvents;
use Foundation\Traits\DisableRefreshDatabase;
use \Illuminate\Support\Facades\Event;

/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 10.03.19
 * Time: 18:35
 */
class GeneratorTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        /* Do not remove this line. It prevents the listener that generate the file from executing */
        Event::fake();
    }

    use DisableRefreshDatabase, DispatchedGeneratorEvents;


    public function testCreateSqlMigration()
    {
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
        GeneratorManager::createMigration("User", "CreateUserCollection", 'users', true);
        Event::assertDispatched(FileGeneratedEvent::class, function (FileGeneratedEvent $event) {
            $this->assertEquals("migration-mongo.stub", $event->getStubName());
            return true;
        });
    }

    public function testCreateFactory()
    {
        $moduleName = "User";
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

    public function testCreateModel()
    {
        $moduleName = "User";
        $fileName = "Address";

        GeneratorManager::createModel($moduleName, $fileName, false, true);

        $expectedFileName = Larapi::getModule($moduleName)->getModels()->getPath() . "/$moduleName$fileName.php";
        $expectedStubName = "model.stub";
        $expectedStubOptions = [
            'NAMESPACE' => 'Modules\User\Entities',
            'CLASS' => 'UserAddress'
        ];

        $modelEvent = $this->getFirstDispatchedEvent(ModelMakeCommand::class);
        $this->assertNotNull($modelEvent);
        $this->assertEquals($expectedFileName, $modelEvent->getFilePath());
        $this->assertEquals($expectedStubName, $modelEvent->getStubName());
        $this->assertEquals($expectedStubOptions, $modelEvent->getStubOptions());

        $expectedStubName = "migration.stub";
        $expectedStubOptions = [
            'CLASS' => 'CreateUserAddressTable',
            'NAMESPACE' => 'Modules\User\Database\Migrations',
            'TABLE' => 'user_addresses'
        ];

        $migrationEvent = $this->getFirstDispatchedEvent(MigrationMakeCommand::class);
        $this->assertNotNull($migrationEvent);
        $this->assertEquals($expectedStubName, $migrationEvent->getStubName());
        $this->assertEquals($expectedStubOptions, $migrationEvent->getStubOptions());
    }

    public function testCreatePolicy()
    {
        $moduleName = "User";
        $fileName = "UserOwnershipPolicy";

        GeneratorManager::createPolicy($moduleName, $fileName);

        $expectedFileName = Larapi::getModule($moduleName)->getPolicies()->getPath() . "/$fileName.php";
        $expectedStubName = "policy.stub";
        $expectedStubOptions = [
            'NAMESPACE' => 'Modules\User\Policies',
            'CLASS' => 'UserOwnershipPolicy'
        ];

        $event = $this->getFirstDispatchedEvent(PolicyMakeCommand::class);
        $this->assertNotNull($event);
        $this->assertEquals($expectedFileName, $event->getFilePath());
        $this->assertEquals($expectedStubName, $event->getStubName());
        $this->assertEquals($expectedStubOptions, $event->getStubOptions());
    }

    public function testCreateTransformer()
    {
        $moduleName = "User";
        $model = "User";
        $fileName = "BlablaTransformer";

        GeneratorManager::createTransformer($moduleName, $fileName, $model);

        $expectedFileName = Larapi::getModule($moduleName)->getTransformers()->getPath() . "/$fileName.php";
        $expectedStubName = "transformer.stub";
        $expectedStubOptions = [
            'NAMESPACE' => 'Modules\User\Transformers',
            'CLASS' => 'BlablaTransformer',
            'MODEL' => 'User',
            'MODEL_NAMESPACE' => 'Modules\User\Entities\User'
        ];

        $event = $this->getFirstDispatchedEvent(TransformerMakeCommand::class);
        $this->assertNotNull($event);
        $this->assertEquals($expectedFileName, $event->getFilePath());
        $this->assertEquals($expectedStubName, $event->getStubName());
        $this->assertEquals($expectedStubOptions, $event->getStubOptions());
    }

    public function testCreateUnitTest()
    {
        $moduleName = "User";
        $fileName = "BlablaUnitTest";

        GeneratorManager::createTest($moduleName, $fileName, 'unit');

        $expectedFileName = Larapi::getModule($moduleName)->getTests()->getPath() . "/$fileName.php";
        $expectedStubName = "unit-test.stub";
        $expectedStubOptions = [
            'NAMESPACE' => 'Modules\User\Tests',
            'CLASS' => 'BlablaUnitTest'
        ];

        $event = $this->getFirstDispatchedEvent(TestMakeCommand::class);
        $this->assertNotNull($event);
        $this->assertEquals($expectedFileName, $event->getFilePath());
        $this->assertEquals($expectedStubName, $event->getStubName());
        $this->assertEquals($expectedStubOptions, $event->getStubOptions());
    }

    public function testCreateRequest()
    {
        $moduleName = "User";
        $fileName = "BlablaRequest";

        GeneratorManager::createRequest($moduleName, $fileName);

        $expectedFileName = Larapi::getModule($moduleName)->getRequests()->getPath() . "/$fileName.php";
        $expectedStubName = "request.stub";
        $expectedStubOptions = [
            'NAMESPACE' => 'Modules\User\Http\Requests',
            'CLASS' => 'BlablaRequest'
        ];

        $event = $this->getFirstDispatchedEvent(RequestMakeCommand::class);
        $this->assertNotNull($event);
        $this->assertEquals($expectedFileName, $event->getFilePath());
        $this->assertEquals($expectedStubName, $event->getStubName());
        $this->assertEquals($expectedStubOptions, $event->getStubOptions());
    }

    public function testCreateRule()
    {
        $moduleName = "User";
        $fileName = "BlalkaRule";

        GeneratorManager::createRule($moduleName, $fileName);

        $expectedFileName = Larapi::getModule($moduleName)->getRules()->getPath() . "/$fileName.php";
        $expectedStubName = "rule.stub";
        $expectedStubOptions = [
            'NAMESPACE' => 'Modules\User\Rules',
            'CLASS' => 'BlalkaRule'
        ];

        $event = $this->getFirstDispatchedEvent(RuleMakeCommand::class);
        $this->assertNotNull($event);
        $this->assertEquals($expectedFileName, $event->getFilePath());
        $this->assertEquals($expectedStubName, $event->getStubName());
        $this->assertEquals($expectedStubOptions, $event->getStubOptions());
    }

    public function testCreateSeeder()
    {
        $moduleName = "User";
        $fileName = "BlablaSeeder";

        GeneratorManager::createSeeder($moduleName, $fileName);

        $expectedFileName = Larapi::getModule($moduleName)->getSeeders()->getPath() . "/$fileName.php";
        $expectedStubName = "seeder.stub";
        $expectedStubOptions = [
            'NAMESPACE' => 'Modules\User\Database\Seeders',
            'CLASS' => 'BlablaSeeder'
        ];

        $event = $this->getFirstDispatchedEvent(SeederMakeCommand::class);
        $this->assertNotNull($event);
        $this->assertEquals($expectedFileName, $event->getFilePath());
        $this->assertEquals($expectedStubName, $event->getStubName());
        $this->assertEquals($expectedStubOptions, $event->getStubOptions());
    }

}
