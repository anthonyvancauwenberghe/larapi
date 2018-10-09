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

    public function cache()
    {
        $this->buildBootstrapArray();
        $this->files->put($this->getCachePath(), serialize($this->bootstrap));
    }

    public function buildBootstrapArray()
    {
        $bootstrap = [];
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
                                    $bootstrap[$key][] = [$directoryPath.'/'.$fileName, $namespace];
                                    break;
                                case 'configs':
                                    $bootstrap[$key][] = [$directoryPath.'/'.$fileName, strtolower($module->getName())];
                                    break;
                                case 'factories':
                                    $bootstrap[$key][] = $directoryPath;
                                    break;
                                case 'migrations':
                                    $bootstrap[$key][] = $directoryPath;
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

    private function loadBootstrapFromCache()
    {
        if (!isset($this->bootstrap)) {
            if (file_exists($this->getCachePath())) {
                $this->bootstrap = unserialize($this->files->get($this->getCachePath()));
            } else {
                $this->cache();
            }
        }

        return $this->bootstrap;
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

    public function getCachePath(): string
    {
        return app()->bootstrapPath().'/cache/'.$this->cacheFile;
    }
}
