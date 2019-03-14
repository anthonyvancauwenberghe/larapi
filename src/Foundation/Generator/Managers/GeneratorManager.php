<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 10.03.19
 * Time: 18:30
 */

namespace Foundation\Generator\Managers;


use Illuminate\Support\Str;

/**
 * Class GeneratorManager
 * @package Foundation\Generator\Managers
 */
class GeneratorManager
{

    /**
     * @var string
     */
    protected $moduleName;

    /**
     * GeneratorManager constructor.
     * @param string $module
     */
    protected function __construct(string $module)
    {
        $this->moduleName = $module;
    }


    /**
     * @param string $moduleName
     * @return GeneratorManager
     */
    public static function module(string $moduleName)
    {
        return new GeneratorManager($moduleName);
    }

    /**
     * @param $options
     * @return mixed
     */
    protected function createOptions($options)
    {
        $options['module']=  Str::studly($this->moduleName);
        return $options;
    }

    /**
     * @param string $migrationName
     * @param string $tableName
     * @param bool $mongo
     */
    public function createMigration(string $migrationName, string $tableName, bool $mongo = false)
    {
        $options = [
            "name" => $migrationName,
            "--table" => $tableName,
            "--mongo" => $mongo
        ];
        \Artisan::call("larapi:make:migration", $this->createOptions($options));
    }

    /**
     * @param string $controllerName
     */
    public function createController(string $controllerName)
    {
        $options = [
            "name" => $controllerName,
        ];
        \Artisan::call("larapi:make:controller", $this->createOptions($options));
    }

    /**
     * @param string $policyName
     */
    public function createPolicy(string $policyName)
    {
        $options = [
            "name" => $policyName,
        ];
        \Artisan::call("larapi:make:policy", $this->createOptions($options));
    }

    /**
     * @param string $eventName
     */
    public function createEvent(string $eventName)
    {
        $options = [
            "name" => $eventName,
        ];
        \Artisan::call("larapi:make:event", $this->createOptions($options));
    }

    /**
     * @param string $notificationName
     */
    public function createNotification(string $notificationName)
    {
        $options = [
            "name" => $notificationName,
        ];
        \Artisan::call("larapi:make:notification", $this->createOptions($options));
    }

    /**
     * @param string $providerName
     */
    public function createServiceProvider(string $providerName)
    {
        $options = [
            "name" => $providerName,
        ];
        \Artisan::call("larapi:make:provider", $this->createOptions($options));
    }

    /**
     * @param string $seederName
     */
    public function createSeeder(string $seederName)
    {
        $options = [
            "name" => $seederName,
        ];
        \Artisan::call("larapi:make:seeder", $this->createOptions($options));
    }

    /**
     * @param string $middlewareName
     */
    public function createMiddleware(string $middlewareName)
    {
        $options = [
            "name" => $middlewareName,
        ];
        \Artisan::call("larapi:make:middleware", $this->createOptions($options));
    }

    /**
     * @param string $requestName
     */
    public function createRequest(string $requestName)
    {
        $options = [
            "name" => $requestName,
        ];
        \Artisan::call("larapi:make:request", $this->createOptions($options));
    }

    /**
     * @param string $ruleName
     */
    public function createRule(string $ruleName)
    {
        $options = [
            "name" => $ruleName,
        ];
        \Artisan::call("larapi:make:rule", $this->createOptions($options));
    }

    /**
     * @param string $moduleName
     */
    public function createRoute()
    {
        $options = [

        ];
        \Artisan::call("larapi:make:route", $this->createOptions($options));
    }

    /**
     * @param string $moduleName
     */
    public function createComposer()
    {
        $options = [

        ];
        \Artisan::call("larapi:make:composer", $this->createOptions($options));
    }

    /**
     * @param string $testName
     * @param string $type
     */
    public function createTest(string $testName, string $type)
    {
        $options = [
            "name" => $testName,
            "--type" => $type
        ];
        \Artisan::call("larapi:make:test", $this->createOptions($options));
    }

    /**
     * @param string $modelName
     */
    public function createFactory(string $modelName)
    {
        $options = [
            "--model" => $modelName,
        ];
        \Artisan::call("larapi:make:factory", $this->createOptions($options));
    }

    /**
     * @param string $transformerName
     * @param string $modelName
     */
    public function createTransformer(string $transformerName, string $modelName)
    {
        $options = [
            "name" => $transformerName,
            "--model" => $modelName,
        ];
        \Artisan::call("larapi:make:transformer", $this->createOptions($options));
    }

    /**
     * @param string $listenerName
     * @param string $eventName
     * @param bool $queued
     */
    public function createListener(string $listenerName, string $eventName, bool $queued = false)
    {
        $options = [
            "name" => $listenerName,
            "--event" => $eventName,
            "--queued" => $queued
        ];
        \Artisan::call("larapi:make:listener", $this->createOptions($options));
    }

    /**
     * @param string $jobName
     * @param bool $sync
     */
    public function createJob(string $jobName, bool $sync = false)
    {
        $options = [
            "name" => $jobName,
            "--sync" => $sync
        ];
        \Artisan::call("larapi:make:job", $this->createOptions($options));
    }

    /**
     * @param string $jobName
     * @param string|null $commandName
     */
    public function createCommand(string $name, ?string $commandName = null)
    {
        $options = [
            "name" => $name,
            "--command" => $commandName
        ];
        \Artisan::call("larapi:make:command", $this->createOptions($options));
    }

    /**
     * @param string $modelName
     * @param bool $mongo
     * @param bool $migration
     */
    public function createModel(string $modelName, bool $mongo = false, bool $migration = true)
    {
        $options = [
            "name" => $modelName,
            "--mongo" => $mongo,
            "--migration" => $migration
        ];
        \Artisan::call("larapi:make:model", $this->createOptions($options));
    }
}
