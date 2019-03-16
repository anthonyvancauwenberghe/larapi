<?php

namespace Foundation\Tests;

use Foundation\Abstracts\Tests\TestCase;
use Foundation\Core\Larapi;
use Foundation\Generator\Events\CommandGeneratedEvent;
use Foundation\Generator\Events\ComposerGeneratedEvent;
use Foundation\Generator\Events\ControllerGeneratedEvent;
use Foundation\Generator\Events\FactoryGeneratedEvent;
use Foundation\Generator\Events\JobGeneratedEvent;
use Foundation\Generator\Events\ListenerGeneratedEvent;
use Foundation\Generator\Events\MiddlewareGeneratedEvent;
use Foundation\Generator\Events\MigrationGeneratedEvent;
use Foundation\Generator\Events\ModelGeneratedEvent;
use Foundation\Generator\Events\NotificationGeneratedEvent;
use Foundation\Generator\Events\PolicyGeneratedEvent;
use Foundation\Generator\Events\ProviderGeneratedEvent;
use Foundation\Generator\Events\RequestGeneratedEvent;
use Foundation\Generator\Events\RouteGeneratedEvent;
use Foundation\Generator\Events\RuleGeneratedEvent;
use Foundation\Generator\Events\SeederGeneratedEvent;
use Foundation\Generator\Events\TestGeneratedEvent;
use Foundation\Generator\Events\TransformerGeneratedEvent;
use Foundation\Generator\Managers\GeneratorManager;
use Foundation\Traits\DispatchedEvents;
use Foundation\Traits\DisableRefreshDatabase;
use \Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;

/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 10.03.19
 * Time: 18:35
 */
class FileGeneratorTest extends TestCase
{
    use DisableRefreshDatabase, DispatchedEvents;

    public function setUp(): void
    {
        parent::setUp();

        /* Do not remove this line. It prevents the listener that generates the files from executing */
        Event::fake();
    }

    protected function getModuleGenerator(string $moduleName) :GeneratorManager{
       return GeneratorManager::module($moduleName,true);
    }

    public function testCreateSqlMigration()
    {
        $moduleName = 'User';
        $this->getModuleGenerator($moduleName)->createMigration("CreateUserTable", 'users', false);

        $module = Larapi::getModule($moduleName);
        $expectedDirectoryPath = $module->getMigrations()->getPath();
        $expectedStubName = "migration.stub";
        $expectedStubOptions = [
            'CLASS' => 'CreateUserTable',
            'NAMESPACE' => $module->getMigrations()->getNamespace(),
            'TABLE' => 'users',
            'MODULE' => 'User'
        ];

        $event = $this->getFirstDispatchedEvent(MigrationGeneratedEvent::class);

        $this->assertNotNull($event);
        $this->assertStringContainsString($expectedDirectoryPath, $event->getFilePath());
        $this->assertEquals($expectedStubName, $event->getStub()->getName());
        $this->assertEquals($expectedStubOptions, $event->getStub()->getOptions());
    }

    public function testCreateMongoMigration()
    {
        $this->getModuleGenerator('User')->createMigration("CreateUserCollection", 'users', true);
        $event = $this->getFirstDispatchedEvent(MigrationGeneratedEvent::class);
        $this->assertNotNull($event);
        $this->assertEquals("migration-mongo.stub", $event->getStub()->getName());
    }

    public function testCreateFactory()
    {
        $moduleName = "User";
        $this->getModuleGenerator($moduleName)->createFactory( "User");

        $expectedFileName = Larapi::getModule($moduleName)->getFactories()->getPath() . '/UserFactory.php';
        $expectedStubName = "factory.stub";
        $expectedStubOptions = [
            'CLASS' => 'UserFactory',
            'MODEL' => 'User',
            'MODEL_NAMESPACE' => 'Modules\User\Entities\User',
            'MODULE' => 'User'
        ];

        $event = $this->getFirstDispatchedEvent(FactoryGeneratedEvent::class);

        $this->assertNotNull($event);
        $this->assertEquals($expectedFileName, $event->getFilePath());
        $this->assertEquals($expectedStubName, $event->getStub()->getName());
        $this->assertEquals($expectedStubOptions, $event->getStub()->getOptions());
    }

    public function testCreateController()
    {
        $moduleName = "User";
        $this->getModuleGenerator($moduleName)->createController( "UserController");

        $expectedFileName = Larapi::getModule($moduleName)->getControllers()->getPath() . '/UserController.php';
        $expectedStubName = "controller.stub";
        $expectedStubOptions = [
            'CLASS' => 'UserController',
            'NAMESPACE' => 'Modules\User\Http\Controllers',
            'MODULE' => 'User'
        ];

        $event = $this->getFirstDispatchedEvent(ControllerGeneratedEvent::class);

        $this->assertNotNull($event);
        $this->assertEquals($expectedFileName, $event->getFilePath());
        $this->assertEquals($expectedStubName, $event->getStub()->getName());
        $this->assertEquals($expectedStubOptions, $event->getStub()->getOptions());
    }

    public function testCreateListener()
    {
        $moduleName = "User";
        $this->getModuleGenerator($moduleName)->createListener("SendWelcomeMail", "UserRegisteredEvent");

        $expectedFileName = Larapi::getModule($moduleName)->getListeners()->getPath() . '/SendWelcomeMail.php';
        $expectedStubName = "listener.stub";
        $expectedStubOptions = [
            'CLASS' => 'SendWelcomeMail',
            'NAMESPACE' => 'Modules\User\Listeners',
            'EVENTNAME' => 'Modules\User\Events\UserRegisteredEvent',
            'SHORTEVENTNAME' => 'UserRegisteredEvent',
            'MODULE' => 'User'
        ];

        $event = $this->getFirstDispatchedEvent(ListenerGeneratedEvent::class);

        $this->assertNotNull($event);
        $this->assertEquals($expectedFileName, $event->getFilePath());
        $this->assertEquals($expectedStubName, $event->getStub()->getName());
        $this->assertEquals($expectedStubOptions, $event->getStub()->getOptions());
    }

    public function testCreateQueuedListener()
    {
        $moduleName = "User";
        $this->getModuleGenerator($moduleName)->createListener("SendWelcomeMail", "UserRegisteredEvent", true);
        $event = $this->getFirstDispatchedEvent(ListenerGeneratedEvent::class);

        $this->assertNotNull($event);
        $this->assertEquals("listener-queued.stub", $event->getStub()->getName());
    }

    public function testCreateJob()
    {
        $moduleName = "User";
        $fileName = "RandomUserJob";
        $this->getModuleGenerator($moduleName)->createJob($fileName, false);

        $expectedFileName = Larapi::getModule($moduleName)->getJobs()->getPath() . "/$fileName.php";
        $expectedStubName = "job-queued.stub";
        $expectedStubOptions = [
            'NAMESPACE' => 'Modules\User\Jobs',
            'CLASS' => 'RandomUserJob',
            'MODULE' => 'User'
        ];

        $event = $this->getFirstDispatchedEvent(JobGeneratedEvent::class);

        $this->assertNotNull($event);
        $this->assertEquals($expectedFileName, $event->getFilePath());
        $this->assertEquals($expectedStubName, $event->getStub()->getName());
        $this->assertEquals($expectedStubOptions, $event->getStub()->getOptions());
    }

    public function testCreateSynchronousJob()
    {
        $moduleName = "User";
        $this->getModuleGenerator($moduleName)->createJob( "AJob", true);
        $event = $this->getFirstDispatchedEvent(JobGeneratedEvent::class);

        $this->assertNotNull($event);
        $this->assertEquals("job.stub", $event->getStub()->getName());
    }

    public function testCreateCommand()
    {
        $moduleName = "User";
        $fileName = "RandomCommand";
        $commandName = "user:dosomethingrandom";

        $this->getModuleGenerator($moduleName)->createCommand($fileName, $commandName);

        $expectedFileName = Larapi::getModule($moduleName)->getCommands()->getPath() . "/$fileName.php";
        $expectedStubName = "command.stub";
        $expectedStubOptions = [
            'NAMESPACE' => 'Modules\User\Console',
            'CLASS' => 'RandomCommand',
            'COMMAND_NAME' => $commandName,
            'MODULE' => 'User'
        ];

        /* @var CommandGeneratedEvent $event */
        $event = $this->getFirstDispatchedEvent(CommandGeneratedEvent::class);

        $this->assertNotNull($event);
        $this->assertEquals($expectedFileName, $event->getFilePath());
        $this->assertEquals($expectedStubName, $event->getStub()->getName());
        $this->assertEquals($expectedStubOptions, $event->getStub()->getOptions());

        $this->assertEquals($commandName,$event->getConsoleCommand());
        //$this->assertEquals($expectedStubOptions, $event->getStub()->getOptions());
    }

    public function testCreateMiddleware()
    {
        $moduleName = "User";
        $fileName = "RandomMiddleware";

        $this->getModuleGenerator($moduleName)->createMiddleware($fileName);

        $expectedFileName = Larapi::getModule($moduleName)->getMiddleWare()->getPath() . "/$fileName.php";
        $expectedStubName = "middleware.stub";
        $expectedStubOptions = [
            'CLASS' => 'RandomMiddleware',
            'NAMESPACE' => 'Modules\User\Http\Middleware',
            'MODULE' => 'User'
        ];

        $event = $this->getFirstDispatchedEvent(MiddlewareGeneratedEvent::class);

        $this->assertNotNull($event);
        $this->assertEquals($expectedFileName, $event->getFilePath());
        $this->assertEquals($expectedStubName, $event->getStub()->getName());
        $this->assertEquals($expectedStubOptions, $event->getStub()->getOptions());
    }

    public function testCreateProvider()
    {
        $moduleName = "User";
        $fileName = "RandomServiceProvider";

        $this->getModuleGenerator($moduleName)->createServiceProvider($fileName);

        $expectedFileName = Larapi::getModule($moduleName)->getServiceProviders()->getPath() . "/$fileName.php";
        $expectedStubName = "provider.stub";
        $expectedStubOptions = [
            'NAMESPACE' => 'Modules\User\Providers',
            'CLASS' => 'RandomServiceProvider',
            'MODULE' => 'User'
        ];

        $event = $this->getFirstDispatchedEvent(ProviderGeneratedEvent::class);

        $this->assertNotNull($event);
        $this->assertEquals($expectedFileName, $event->getFilePath());
        $this->assertEquals($expectedStubName, $event->getStub()->getName());
        $this->assertEquals($expectedStubOptions, $event->getStub()->getOptions());
    }

    public function testCreateNotification()
    {
        $moduleName = "User";
        $fileName = "RandomNotification";

        $this->getModuleGenerator($moduleName)->createNotification($fileName);

        $expectedFileName = Larapi::getModule($moduleName)->getNotifications()->getPath() . "/$fileName.php";
        $expectedStubName = "notification.stub";
        $expectedStubOptions = [
            'NAMESPACE' => 'Modules\User\Notifications',
            'CLASS' => 'RandomNotification',
            'MODULE' => 'User'
        ];

        $event = $this->getFirstDispatchedEvent(NotificationGeneratedEvent::class);

        $this->assertNotNull($event);
        $this->assertEquals($expectedFileName, $event->getFilePath());
        $this->assertEquals($expectedStubName, $event->getStub()->getName());
        $this->assertEquals($expectedStubOptions, $event->getStub()->getOptions());
    }

    public function testCreateModel()
    {
        $moduleName = "User";
        $fileName = "Address";

        $this->getModuleGenerator($moduleName)->createModel($fileName, false, true);

        $expectedFileName = Larapi::getModule($moduleName)->getModels()->getPath() . "/$moduleName$fileName.php";
        $expectedStubName = "model.stub";
        $expectedStubOptions = [
            'NAMESPACE' => 'Modules\User\Entities',
            'CLASS' => 'UserAddress',
            'MODULE' => 'User'
        ];

        $modelEvent = $this->getFirstDispatchedEvent(ModelGeneratedEvent::class);
        $this->assertNotNull($modelEvent);
        $this->assertEquals($expectedFileName, $modelEvent->getFilePath());
        $this->assertEquals($expectedStubName, $modelEvent->getStub()->getName());
        $this->assertEquals($expectedStubOptions, $modelEvent->getStub()->getOptions());

        $expectedStubName = "migration.stub";
        $expectedStubOptions = [
            'CLASS' => 'CreateUserAddressTable',
            'NAMESPACE' => 'Modules\User\Database\Migrations',
            'TABLE' => 'user_addresses',
            'MODULE' => 'User'
        ];

        $migrationEvent = $this->getFirstDispatchedEvent(MigrationGeneratedEvent::class);
        $this->assertNotNull($migrationEvent);
        $this->assertEquals($expectedStubName, $migrationEvent->getStub()->getName());
        $this->assertEquals($expectedStubOptions, $migrationEvent->getStub()->getOptions());
    }

    public function testCreatePolicy()
    {
        $moduleName = "User";
        $fileName = "UserOwnershipPolicy";

        $this->getModuleGenerator($moduleName)->createPolicy($fileName);

        $expectedFileName = Larapi::getModule($moduleName)->getPolicies()->getPath() . "/$fileName.php";
        $expectedStubName = "policy.stub";
        $expectedStubOptions = [
            'NAMESPACE' => 'Modules\User\Policies',
            'CLASS' => 'UserOwnershipPolicy',
            'MODULE' => 'User'
        ];

        $event = $this->getFirstDispatchedEvent(PolicyGeneratedEvent::class);
        $this->assertNotNull($event);
        $this->assertEquals($expectedFileName, $event->getFilePath());
        $this->assertEquals($expectedStubName, $event->getStub()->getName());
        $this->assertEquals($expectedStubOptions, $event->getStub()->getOptions());
    }

    public function testCreateTransformer()
    {
        $moduleName = "User";
        $model = "User";
        $fileName = "BlablaTransformer";

        $this->getModuleGenerator($moduleName)->createTransformer($fileName, $model);

        $expectedFileName = Larapi::getModule($moduleName)->getTransformers()->getPath() . "/$fileName.php";
        $expectedStubName = "transformer.stub";
        $expectedStubOptions = [
            'NAMESPACE' => 'Modules\User\Transformers',
            'CLASS' => 'BlablaTransformer',
            'MODEL' => 'User',
            'MODEL_NAMESPACE' => 'Modules\User\Entities\User',
            'MODULE' => 'User'
        ];

        $event = $this->getFirstDispatchedEvent(TransformerGeneratedEvent::class);
        $this->assertNotNull($event);
        $this->assertEquals($expectedFileName, $event->getFilePath());
        $this->assertEquals($expectedStubName, $event->getStub()->getName());
        $this->assertEquals($expectedStubOptions, $event->getStub()->getOptions());
    }

    public function testCreateUnitTest()
    {
        $moduleName = "User";
        $fileName = "AUserUnitTest";

        $this->getModuleGenerator($moduleName)->createTest($fileName, 'unit');

        $expectedFileName = Larapi::getModule($moduleName)->getTests()->getPath() . "/$fileName.php";
        $expectedStubName = "unit-test.stub";
        $expectedStubOptions = [
            'NAMESPACE' => 'Modules\User\Tests',
            'CLASS' => $fileName,
            'MODULE' => $moduleName,
            'TYPE' => 'unit'
        ];

        $event = $this->getFirstDispatchedEvent(TestGeneratedEvent::class);
        $this->assertNotNull($event);
        $this->assertEquals($expectedFileName, $event->getFilePath());
        $this->assertEquals($expectedStubName, $event->getStub()->getName());
        $this->assertEquals($expectedStubOptions, $event->getStub()->getOptions());
    }

    public function testCreateRequest()
    {
        $moduleName = "User";
        $fileName = "ARequest";

        $this->getModuleGenerator($moduleName)->createRequest($fileName);

        $expectedFileName = Larapi::getModule($moduleName)->getRequests()->getPath() . "/$fileName.php";
        $expectedStubName = "request.stub";
        $expectedStubOptions = [
            'NAMESPACE' => 'Modules\User\Http\Requests',
            'CLASS' => $fileName,
            'MODULE' => $moduleName
        ];

        $event = $this->getFirstDispatchedEvent(RequestGeneratedEvent::class);
        $this->assertNotNull($event);
        $this->assertEquals($expectedFileName, $event->getFilePath());
        $this->assertEquals($expectedStubName, $event->getStub()->getName());
        $this->assertEquals($expectedStubOptions, $event->getStub()->getOptions());
    }

    public function testCreateRule()
    {
        $moduleName = "User";
        $fileName = "BlalkaRule";

        $this->getModuleGenerator($moduleName)->createRule($fileName);

        $expectedFileName = Larapi::getModule($moduleName)->getRules()->getPath() . "/$fileName.php";
        $expectedStubName = "rule.stub";
        $expectedStubOptions = [
            'NAMESPACE' => 'Modules\User\Rules',
            'CLASS' => 'BlalkaRule',
            'MODULE' => 'User'
        ];

        $event = $this->getFirstDispatchedEvent(RuleGeneratedEvent::class);
        $this->assertNotNull($event);
        $this->assertEquals($expectedFileName, $event->getFilePath());
        $this->assertEquals($expectedStubName, $event->getStub()->getName());
        $this->assertEquals($expectedStubOptions, $event->getStub()->getOptions());
    }

    public function testCreateSeeder()
    {
        $moduleName = "User";
        $fileName = "BlablaSeeder";

        $this->getModuleGenerator($moduleName)->createSeeder($fileName);

        $expectedFileName = Larapi::getModule($moduleName)->getSeeders()->getPath() . "/$fileName.php";
        $expectedStubName = "seeder.stub";
        $expectedStubOptions = [
            'NAMESPACE' => 'Modules\User\Database\Seeders',
            'CLASS' => 'BlablaSeeder',
            'MODULE' => 'User'
        ];

        $event = $this->getFirstDispatchedEvent(SeederGeneratedEvent::class);
        $this->assertNotNull($event);
        $this->assertEquals($expectedFileName, $event->getFilePath());
        $this->assertEquals($expectedStubName, $event->getStub()->getName());
        $this->assertEquals($expectedStubOptions, $event->getStub()->getOptions());
    }

    public function testCreateRoute()
    {
        $moduleName = "Demo";
        $routename = strtolower(Str::plural($moduleName)) . '.v1';

        $this->getModuleGenerator($moduleName)->createRoute();

        $expectedFileName = Larapi::getModule($moduleName)->getRoutes()->getPath() . "/$routename.php";
        $expectedStubName = "route.stub";
        $expectedStubOptions = [
            'MODULE_NAME' => 'Demo',
            'CAPS_MODULE_NAME' => 'DEMO',
            'VERSION' => 'v1',
            'MODULE' => 'Demo'
        ];

        $event = $this->getFirstDispatchedEvent(RouteGeneratedEvent::class);
        $this->assertNotNull($event);
        $this->assertEquals($expectedFileName, $event->getFilePath());
        $this->assertEquals($expectedStubName, $event->getStub()->getName());
        $this->assertEquals($expectedStubOptions, $event->getStub()->getOptions());
    }

    public function testCreateComposer()
    {
        $moduleName = "Demo";

        $this->getModuleGenerator($moduleName)->createComposer();

        $expectedFileName = Larapi::getModule($moduleName)->getPath() . "/composer.json";
        $expectedStubName = "composer.stub";
        $expectedStubOptions = [
            'LOWER_MODULE_NAME' => 'demo',
            'AUTHOR_NAME' => 'arthur devious',
            'AUTHOR_MAIL' => 'aamining2@gmail.com',
            'MODULE' => 'Demo'
        ];

        $event = $this->getFirstDispatchedEvent(ComposerGeneratedEvent::class);
        $this->assertNotNull($event);
        $this->assertEquals($expectedFileName, $event->getFilePath());
        $this->assertEquals($expectedStubName, $event->getStub()->getName());
        $this->assertEquals($expectedStubOptions, $event->getStub()->getOptions());
    }

}
