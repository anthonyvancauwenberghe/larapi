<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 04.10.18
 * Time: 02:13.
 */

namespace Foundation\Services;

use Illuminate\Console\Command;

class BootstrapRegistrarService
{
    protected $files;

    protected $moduleEntityDirectories = [
        'commands'   => 'Console',
        'routes'     => 'Routes',
        'configs'    => 'Config',
        'factories'  => 'Database/factories',
        'migrations' => 'Database/Migrations',
        'seeders'    => 'Database/Seeders',
    ];

    protected $cacheFile = 'bootstrap.php';

    protected $bootstrap;

    public function __construct()
    {
        $this->files = new \Illuminate\Filesystem\Filesystem();
    }

    public function recache()
    {
        $this->buildBootstrapData();
        $this->storeInCache($this->bootstrap);
    }

    private function storeInCache($data)
    {
        file_put_contents($this->getCachePath(), '<?php return '.var_export($data, true).';');
    }

    public function readFromCache()
    {
        return include $this->getCachePath();
    }

    public function clearCache()
    {
        unlink($this->getCachePath());
    }

    public function cacheExists()
    {
        return file_exists($this->getCachePath());
    }

    private function getCachePath(): string
    {
        return app()->bootstrapPath().'/cache/'.$this->cacheFile;
    }

    private function buildEmptyBootstrapArray()
    {
        $bootstrapArray = [];
        foreach ($this->moduleEntityDirectories as $key => $directory) {
            $bootstrapArray[$key] = [];
        }

        return $bootstrapArray;
    }

    private function buildBootstrapData()
    {
        $bootstrap = $this->buildEmptyBootstrapArray();
        foreach (\Module::all() as $module) {
            foreach ($this->moduleEntityDirectories as $key => $directory) {
                $directory = ucfirst($directory);
                $directoryPath = $module->getPath().'/'.$directory;
                $namespace = 'Modules'.'\\'.$module->getName();
                if (file_exists($directoryPath)) {
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
                                    $bootstrap[$key][] = $this->buildRouteArray($directoryPath.'/'.$fileName, $namespace);
                                    break;
                                case 'configs':
                                    $bootstrap[$key][] = $this->buildConfigArray($directoryPath.'/'.$fileName, $module->getName());
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
                                default:
                                    break;
                            }
                        }
                    }
                }
            }
        }

        $this->bootstrap = $bootstrap;
    }

    private function hasPhpExtension(string $fileName): bool
    {
        return strlen($fileName) > 4 && '.php' === ($fileName[-4].$fileName[-3].$fileName[-2].$fileName[-1]);
    }

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

    private function buildRouteArray($path, $namespace)
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
        ];
    }

    private function buildConfigArray($path, $module)
    {
        return [
            'path'   => $path,
            'module' => strtolower($module),
        ];
    }

    private function buildDirectoryPathArray($path)
    {
        return [
            'path' => $path,
        ];
    }

    public function getCommands(): array
    {
        return $this->loadBootstrapFromCache()['commands'] ?? [];
    }

    public function getRoutes(): array
    {
        return $this->loadBootstrapFromCache()['routes'] ?? [];
    }

    public function getConfigs(): array
    {
        return $this->loadBootstrapFromCache()['configs'] ?? [];
    }

    public function getFactories(): array
    {
        return $this->loadBootstrapFromCache()['factories'] ?? [];
    }

    public function getMigrations(): array
    {
        return $this->loadBootstrapFromCache()['migrations'] ?? [];
    }

    public function getSeeders(): array
    {
        return $this->loadBootstrapFromCache()['seeders'] ?? [];
    }
}
