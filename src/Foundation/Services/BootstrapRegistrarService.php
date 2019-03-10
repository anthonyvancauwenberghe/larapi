<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 04.10.18
 * Time: 02:13.
 */

namespace Foundation\Services;

use Foundation\Abstracts\Listeners\Listener;
use Foundation\Core\Larapi;
use Foundation\Core\Resource;
use Illuminate\Console\Command;

class BootstrapRegistrarService
{
    /**
     * @var array
     */
    protected $moduleEntityDirectories = [
        'commands' => 'Console',
        'routes' => 'Routes',
        'configs' => 'Config',
        'factories' => 'Database/factories',
        'migrations' => 'Database/Migrations',
        'seeders' => 'Database/Seeders',
        'models' => 'Entities',
        'policies' => 'Policies',
        'providers' => 'Providers',
        'events' => 'Events',
    ];

    /**
     * @var string
     */
    protected $cacheFile = 'bootstrap.php';

    /**
     * @var
     */
    protected $bootstrap;

    protected $bootstrap2;

    public function recache()
    {
        $this->buildBootstrapData();

        $this->storeInCache($this->bootstrap2);
    }

    /**
     * @param $data
     */
    private function storeInCache($data)
    {
        file_put_contents($this->getCachePath(), '<?php return ' . var_export($data, true) . ';');
    }

    /**
     * @return mixed
     */
    public function readFromCache()
    {
        return include $this->getCachePath();
    }

    public function clearCache()
    {
        unlink($this->getCachePath());
    }

    /**
     * @return bool
     */
    public function cacheExists()
    {
        return file_exists($this->getCachePath());
    }

    /**
     * @return string
     */
    private function getCachePath(): string
    {
        return app()->bootstrapPath() . '/cache/' . $this->cacheFile;
    }

    /**
     * @return array
     */
    private function buildEmptyBootstrapArray()
    {
        $bootstrapArray = [];
        foreach ($this->moduleEntityDirectories as $key => $directory) {
            $bootstrapArray[$key] = [];
        }
        return $bootstrapArray;
    }

    private function bootstrap()
    {
        foreach (Larapi::getModules() as $module) {
            $this->bootstrapCommands($module);
            $this->bootstrapRoutes($module);
            $this->bootstrapConfigs($module);
            $this->bootstrapFactories($module);
            $this->bootstrapMigrations($module);
            $this->bootstrapModels($module);
            $this->bootstrapPolicies($module);
            $this->bootstrapSeeders($module);
            $this->bootstrapProviders($module);
            $this->bootstrapEvents($module);
            $this->bootstrapObservers($module);
        }
    }

    /**
     * @return void
     */
    private function buildBootstrapData()
    {
        $bootstrap = $this->buildEmptyBootstrapArray();
        $modules = Larapi::getModules();
        foreach ($modules as $module) {
            if (is_dir($module->getPath())) {
                foreach ($this->moduleEntityDirectories as $key => $directory) {
                    $directory = ucfirst($directory);
                    $directoryPath = $module->getPath() . '/' . $directory;
                    $namespace = 'Modules' . '\\' . $module->getName();
                    if (is_dir($directoryPath)) {
                        $files = scandir($directoryPath);
                        foreach ($files as $fileName) {
                            if ($this->hasPhpExtension($fileName)) {
                                $className = basename($fileName, '.php');
                                $class = $namespace . '\\' . str_replace('/', '\\', $directory) . '\\' . $className;
                                switch ($key) {
                                    case 'commands':
                                        try {
                                            $command = new $class();
                                            if ($command instanceof Command) {
                                                $bootstrap[$key][] = $class;
                                            }
                                        } catch (\Exception $e) {
                                            break;
                                        }
                                        break;
                                    case 'routes':
                                        $bootstrap[$key][] = $this->buildRouteArray($directoryPath . '/' . $fileName, $namespace, $fileName);
                                        break;
                                    case 'configs':
                                        $bootstrap[$key][] = $this->buildConfigArray($directoryPath . '/' . $fileName, $module->getName(), $fileName);
                                        break;
                                    case 'factories':
                                        $bootstrap[$key][] = $this->buildDirectoryPathArray($directoryPath);
                                        break;
                                    case 'migrations':
                                        $bootstrap[$key][] = $this->buildDirectoryPathArray($directoryPath);
                                        break;
                                    case 'seeders':
                                        $bootstrap[$key][] = $class;
                                        break;
                                    case 'models':
                                        $bootstrap[$key][] = $class;
                                        break;
                                    case 'policies':
                                        $bootstrap[$key][] = $this->buildPolicyArray($class, $namespace);
                                        break;
                                    case 'providers':
                                        $bootstrap[$key][] = $class;
                                        break;
                                    case 'events':
                                        $bootstrap[$key][] = $this->buildEventsArray($class);
                                        break;
                                    default:
                                        break;
                                }
                            }
                        }
                    }
                }
            }
        }

        $this->bootstrap2 = $bootstrap;
    }

    private function bootstrapCommands(\Foundation\Core\Module $module)
    {
        $this->bootstrap['commands'][] = $module->getCommands();
    }

    private function bootstrapRoutes(\Foundation\Core\Module $module)
    {
        $this->bootstrap['routes'][] = $module->getRoutes();
    }

    private function bootstrapConfigs(\Foundation\Core\Module $module)
    {
        $this->bootstrap['configs'][] = $module->getConfigs();
    }

    private function bootstrapFactories(\Foundation\Core\Module $module)
    {
        $this->bootstrap['factories'][] = $module->getFactories();
    }

    private function bootstrapMigrations(\Foundation\Core\Module $module)
    {
        $this->bootstrap['migrations'][] = $module->getMigrations();
    }

    private function bootstrapSeeders(\Foundation\Core\Module $module)
    {
        $this->bootstrap['seeders'][] = $module->getSeeders();
    }

    private function bootstrapModels(\Foundation\Core\Module $module)
    {
        $this->bootstrap['models'][] = $module->getModels();
    }

    private function bootstrapPolicies(\Foundation\Core\Module $module)
    {
        $this->bootstrap['policies'][] = $module->getPolicies();
    }

    private function bootstrapProviders(\Foundation\Core\Module $module)
    {
        $this->bootstrap['providers'][] = $module->getProviders();
    }

    private function bootstrapEvents(\Foundation\Core\Module $module)
    {
        $this->bootstrap['events'][] = $module->getEvents();
    }

    private function bootstrapObservers(\Foundation\Core\Module $module)
    {
        $this->bootstrap['observers'][] = $module->getObservers();
    }

    /**
     * @param string $fileName
     *
     * @return bool
     */
    private function hasPhpExtension(string $fileName): bool
    {
        return strlen($fileName) > 4 && '.php' === ($fileName[-4] . $fileName[-3] . $fileName[-2] . $fileName[-1]);
    }

    /**
     * @return mixed
     */
    public function loadBootstrapFromCache()
    {
        if (!isset($this->bootstrap2)) {
            if ($this->cacheExists()) {
                $this->bootstrap2 = $this->readFromCache();
            } else {
                $this->recache();
            }
        }

        return $this->bootstrap2;
    }

    public function loadNewBootstrap(){
        $this->bootstrap();
        return $this->bootstrap;
    }

    /**
     * @param $path
     * @param $namespace
     *
     * @return array
     */
    private function buildRouteArray($path, $namespace, $fileName)
    {
        $apiDomain = strtolower(env('API_URL'));
        $apiDomain = str_replace('http://', '', $apiDomain);
        $apiDomain = str_replace('https://', '', $apiDomain);
        $moduleNamespace = $namespace;
        $moduleName = explode('\\', $moduleNamespace)[1];
        $controllerNamespace = $moduleNamespace . '\\' . 'Http\\Controllers';
        $modelNameSpace = $moduleNamespace . '\\' . 'Entities\\' . $moduleName;

        return [
            'path' => $path,
            'namespace' => $namespace,
            'module' => strtolower($moduleName),
            'domain' => $apiDomain,
            'controller' => $controllerNamespace,
            'model' => $modelNameSpace,
            'filename' => $fileName,
        ];
    }

    /**
     * @param $class
     * @param $namespace
     *
     * @return array
     */
    private function buildPolicyArray($class, $namespace)
    {
        $moduleNamespace = $namespace;
        $moduleName = explode('\\', $moduleNamespace)[1];
        $modelNameSpace = $moduleNamespace . '\\' . 'Entities\\' . $moduleName;

        return [
            'class' => $class,
            'model' => $modelNameSpace,
        ];
    }

    /**
     * @param $path
     * @param $module
     *
     * @return array
     */
    private function buildConfigArray($path, $module, $filename)
    {
        return [
            'path' => $path,
            'filename' => $filename,
            'module' => strtolower($module),
        ];
    }

    /**
     * @param $path
     *
     * @return array
     */
    private function buildDirectoryPathArray($path)
    {
        return [
            'path' => $path,
        ];
    }

    private function buildEventsArray($class)
    {
        $listenerProperties = get_class_property($class, 'listeners') ?? [];
        $listeners = [];
        foreach ($listenerProperties as $listener) {
            if ($listener instanceof Listener ) {
                $listeners[] = $listener;
            }
        }

        return [
            'class' => $class,
            'listeners' => $listeners,
        ];
    }

    /**
     * @return Resource[]
     */
    public function getCommands(): array
    {
        return $this->loadBootstrapFromCache()['commands'] ?? [];
    }

    /**
     * @return Resource[]
     */
    public function getRoutes(): array
    {
        return $this->loadBootstrapFromCache()['routes'] ?? [];
    }

    /**
     * @return Resource[]
     */
    public function getConfigs(): array
    {
        return $this->loadBootstrapFromCache()['configs'] ?? [];
    }

    /**
     * @return Resource[]
     */
    public function getFactories(): array
    {
        return $this->loadBootstrapFromCache()['factories'] ?? [];
    }

    /**
     * @return Resource[]
     */
    public function getMigrations(): array
    {
        return $this->loadBootstrapFromCache()['migrations'] ?? [];
    }

    /**
     * @return Resource[]
     */
    public function getSeeders(): array
    {
        return $this->loadBootstrapFromCache()['seeders'] ?? [];
    }

    /**
     * @return Resource[]
     */
    public function getModels(): array
    {
        return $this->loadBootstrapFromCache()['models'] ?? [];
    }

    /**
     * @return Resource[]
     */
    public function getPolicies(): array
    {
        return $this->loadBootstrapFromCache()['policies'] ?? [];
    }

    /**
     * @return Resource[]
     */
    public function getProviders(): array
    {
        return $this->loadBootstrapFromCache()['providers'] ?? [];
    }

    /**
     * @return Resource[]
     */
    public function getEvents(): array
    {
        return $this->loadBootstrapFromCache()['events'] ?? [];
    }
}
