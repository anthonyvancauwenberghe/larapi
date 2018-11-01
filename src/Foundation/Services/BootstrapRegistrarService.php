<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 04.10.18
 * Time: 02:13.
 */

namespace Foundation\Services;

use Foundation\Abstracts\Listeners\ListenerContract;
use Illuminate\Console\Command;
use Nwidart\Modules\Module;

class BootstrapRegistrarService
{
    /**
     * @var array
     */
    protected $moduleEntityDirectories = [
        'commands'   => 'Console',
        'routes'     => 'Routes',
        'configs'    => 'Config',
        'factories'  => 'Database/factories',
        'migrations' => 'Database/Migrations',
        'seeders'    => 'Database/Seeders',
        'models'     => 'Entities',
        'policies'   => 'Policies',
        'providers'  => 'Providers',
        'events'     => 'Events',
    ];

    /**
     * @var string
     */
    protected $cacheFile = 'bootstrap.php';

    /**
     * @var
     */
    protected $bootstrap;

    public function recache()
    {
        $this->buildBootstrapData();
        $this->storeInCache($this->bootstrap);
    }

    /**
     * @param $data
     */
    private function storeInCache($data)
    {
        file_put_contents($this->getCachePath(), '<?php return '.var_export($data, true).';');
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
        return app()->bootstrapPath().'/cache/'.$this->cacheFile;
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

    /**
     * @return string[]
     */
    private function getModules(): array
    {
        $path = base_path('src/Modules');
        $moduleNames = array_diff(scandir($path), ['..', '.']);
        $modules = [];
        foreach ($moduleNames as $moduleName) {
            $modules[$moduleName] = $path.'/'.$moduleName;
        }

        return $modules;
    }

    /**
     * @return void
     */
    private function buildBootstrapData()
    {
        $bootstrap = $this->buildEmptyBootstrapArray();
        $moduleNames = $this->getModules();
        foreach ($moduleNames as $moduleName => $modulePath) {
            if (is_dir($modulePath)) {
                foreach ($this->moduleEntityDirectories as $key => $directory) {
                    $directory = ucfirst($directory);
                    $directoryPath = $modulePath.'/'.$directory;
                    $namespace = 'Modules'.'\\'.$moduleName;
                    if (is_dir($directoryPath)) {
                        $files = scandir($directoryPath);
                        foreach ($files as $fileName) {
                            if ($this->hasPhpExtension($fileName)) {
                                $className = basename($fileName, '.php');
                                $class = $namespace.'\\'.str_replace('/', '\\', $directory).'\\'.$className;
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
                                        $bootstrap[$key][] = $this->buildRouteArray($directoryPath.'/'.$fileName, $namespace,$fileName);
                                        break;
                                    case 'configs':
                                        $bootstrap[$key][] = $this->buildConfigArray($directoryPath.'/'.$fileName, $moduleName, $fileName);
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

        $this->bootstrap = $bootstrap;
    }

    /**
     * @param string $fileName
     *
     * @return bool
     */
    private function hasPhpExtension(string $fileName): bool
    {
        return strlen($fileName) > 4 && '.php' === ($fileName[-4].$fileName[-3].$fileName[-2].$fileName[-1]);
    }

    /**
     * @return mixed
     */
    public function loadBootstrapFromCache()
    {
        if (!isset($this->bootstrap)) {
            if ($this->cacheExists()) {
                $this->bootstrap = $this->readFromCache();
            } else {
                $this->recache();
            }
        }

        return $this->bootstrap;
    }

    /**
     * @param $path
     * @param $namespace
     *
     * @return array
     */
    private function buildRouteArray($path, $namespace,$fileName)
    {
        $apiDomain = strtolower(env('API_URL'));
        $apiDomain = str_replace('http://', '', $apiDomain);
        $apiDomain = str_replace('https://', '', $apiDomain);
        $moduleNamespace = $namespace;
        $moduleName = explode('\\', $moduleNamespace)[1];
        $controllerNamespace = $moduleNamespace.'\\'.'Http\\Controllers';
        $modelNameSpace = $moduleNamespace.'\\'.'Entities\\'.$moduleName;

        return [
            'path'       => $path,
            'namespace'  => $namespace,
            'module'     => strtolower($moduleName),
            'domain'     => $apiDomain,
            'controller' => $controllerNamespace,
            'model'      => $modelNameSpace,
            'filename'   => $fileName
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
        $modelNameSpace = $moduleNamespace.'\\'.'Entities\\'.$moduleName;

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
            'path'     => $path,
            'filename' => $filename,
            'module'   => strtolower($module),
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
            if (class_implements_interface($listener, ListenerContract::class)) {
                $listeners[] = $listener;
            }
        }

        return [
            'class'     => $class,
            'listeners' => $listeners,
        ];
    }

    /**
     * @return array
     */
    public function getCommands(): array
    {
        return $this->loadBootstrapFromCache()['commands'] ?? [];
    }

    /**
     * @return array
     */
    public function getRoutes(): array
    {
        return $this->loadBootstrapFromCache()['routes'] ?? [];
    }

    /**
     * @return array
     */
    public function getConfigs(): array
    {
        return $this->loadBootstrapFromCache()['configs'] ?? [];
    }

    /**
     * @return array
     */
    public function getFactories(): array
    {
        return $this->loadBootstrapFromCache()['factories'] ?? [];
    }

    /**
     * @return array
     */
    public function getMigrations(): array
    {
        return $this->loadBootstrapFromCache()['migrations'] ?? [];
    }

    /**
     * @return array
     */
    public function getSeeders(): array
    {
        return $this->loadBootstrapFromCache()['seeders'] ?? [];
    }

    /**
     * @return array
     */
    public function getModels(): array
    {
        return $this->loadBootstrapFromCache()['models'] ?? [];
    }

    /**
     * @return array
     */
    public function getPolicies(): array
    {
        return $this->loadBootstrapFromCache()['policies'] ?? [];
    }

    /**
     * @return array
     */
    public function getProviders(): array
    {
        return $this->loadBootstrapFromCache()['providers'] ?? [];
    }

    /**
     * @return array
     */
    public function getEvents(): array
    {
        return $this->loadBootstrapFromCache()['events'] ?? [];
    }
}
