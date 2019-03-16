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
     * @var bool
     */
    protected $overwrite;

    /**
     * GeneratorManager constructor.
     * @param string $module
     */
    protected function __construct(string $module, bool $overwrite = false)
    {
        $this->moduleName = $module;
        $this->overwrite = $overwrite;
    }

    /**
     * @param string $moduleName
     * @return GeneratorManager
     */
    public static function module(string $moduleName, bool $overwrite = false)
    {
        return new GeneratorManager($moduleName, $overwrite);
    }

    protected function call(string $commandName, $options)
    {
        \Artisan::call("larapi:make:$commandName", $this->alterOptions($options));
    }

    /**
     * @param $options
     * @return mixed
     */
    protected function alterOptions($options)
    {
        $options['module'] = Str::studly($this->moduleName);
        if ($this->overwrite)
            $options['--overwrite'] = null;
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
        $this->call('migration', $options);
    }

    /**
     * @param string $controllerName
     */
    public function createController(string $controllerName)
    {
        $options = [
            "name" => $controllerName,
        ];
        $this->call('controller', $options);
    }

    /**
     * @param string $policyName
     */
    public function createPolicy(string $policyName)
    {
        $options = [
            "name" => $policyName,
        ];
        $this->call('policy', $options);
    }

    /**
     * @param string $eventName
     */
    public function createEvent(string $eventName)
    {
        $options = [
            "name" => $eventName,
        ];
        $this->call('event', $options);
    }

    /**
     * @param string $notificationName
     */
    public function createNotification(string $notificationName)
    {
        $options = [
            "name" => $notificationName,
        ];
        $this->call('notification', $options);
    }

    /**
     * @param string $providerName
     */
    public function createServiceProvider(string $providerName)
    {
        $options = [
            "name" => $providerName,
        ];
        $this->call('provider', $options);
    }

    /**
     * @param string $seederName
     */
    public function createSeeder(string $seederName)
    {
        $options = [
            "name" => $seederName,
        ];
        $this->call('seeder', $options);
    }

    /**
     * @param string $middlewareName
     */
    public function createMiddleware(string $middlewareName)
    {
        $options = [
            "name" => $middlewareName,
        ];
        $this->call('middleware', $options);
    }

    /**
     * @param string $requestName
     */
    public function createRequest(string $requestName)
    {
        $options = [
            "name" => $requestName,
        ];
        $this->call('request', $options);
    }

    /**
     * @param string $ruleName
     */
    public function createRule(string $ruleName)
    {
        $options = [
            "name" => $ruleName,
        ];
        $this->call('rule', $options);
    }

    /**
     * @param string $moduleName
     */
    public function createRoute()
    {
        $options = [

        ];
        $this->call('route', $options);
    }

    /**
     * @param string $moduleName
     */
    public function createComposer()
    {
        $options = [

        ];
        $this->call('composer', $options);
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
        $this->call('test', $options);
    }

    /**
     * @param string $modelName
     */
    public function createFactory(string $modelName)
    {
        $options = [
            "--model" => $modelName,
        ];
        $this->call('factory', $options);
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
        $this->call('transformer', $options);
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
        $this->call('listener', $options);
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
        $this->call('job', $options);
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
        $this->call('command', $options);
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
        $this->call('model', $options);
    }
}
