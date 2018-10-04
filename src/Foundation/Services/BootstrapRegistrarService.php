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
        'console'
    ];

    protected $cacheFile = 'bootstrap.php';

    protected $bootstrap;

    public function __construct()
    {
        $this->files = new \Illuminate\Filesystem\Filesystem();
    }

    public function cache()
    {
        $boostrap = $this->scanDirectories();
        $this->bootstrap = $boostrap;
        $this->files->put($this->getCachePath(), serialize($boostrap));
    }

    public function scanDirectories(): array
    {
        $bootstrap = [];
        $modules = \Module::all();

        foreach ($modules as $module) {
            foreach ($this->moduleEntityDirectories as $directory) {
                $directory = ucfirst($directory);
                $directoryPath = $module->getPath() . '/' . $directory;
                $namespace = 'Modules' . '\\' . $module->getName();
                if (file_exists($directoryPath)) {
                    $files = scandir($directoryPath);
                    foreach ($files as $fileName) {
                        if ($this->isPhpFile($fileName)) {
                            $className = basename($fileName, '.php');
                            $class = $namespace . '\\' . $directory . '\\' . $className;
                            try {
                                if (new $class() instanceof Command) {
                                    $bootstrap['commands'][] = $class;
                                }
                            } catch (\Exception $e) {
                            }
                        }
                    }
                }
            }
        }

        return $bootstrap;
    }

    private function isPhpFile(string $fileName): bool
    {
        return strlen($fileName) > 5 && '.php' === ($fileName[-4] . $fileName[-3] . $fileName[-2] . $fileName[-1]);
    }

    private function loadBootstrapFromCache()
    {
        if (!isset($this->bootstrap)) {
            $commandCachePath = $this->getCachePath();
            $this->cache();
            $this->boostrap = unserialize($this->files->get($commandCachePath));
        }
        return $this->bootstrap;
    }

    public function getCommands(): array
    {
        return $this->loadBootstrapFromCache()['commands'];
    }

    public function getCachePath(): string
    {
        return app()->bootstrapPath() . '/cache/' . $this->cacheFile;
    }
}
