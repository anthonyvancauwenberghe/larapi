<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 04.10.18
 * Time: 02:13
 */

namespace Foundation\Services;


use Illuminate\Console\Command;

class CommandRegistrationService
{
    protected $files;

    protected $moduleCommandDirectoryNames = [
        'console',
        'commands'
    ];

    protected $cacheFile = 'commands.php';

    public function __construct()
    {
        $this->files = new \Illuminate\Filesystem\Filesystem();
    }

    public function cacheCommands()
    {
        $commands = $this->scanForCommands();

        $this->files->put(
            $this->getCachePath(), json_encode($commands)
        );
    }

    public function scanForCommands(): array
    {
        $commands = [];

        foreach (\Module::all() as $module) {
            foreach ($this->moduleCommandDirectoryNames as $directory) {
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
                                if (new $class instanceof Command)
                                    $commands[] = $class;
                            } catch (\Exception $e) {
                            }
                        }
                    }
                }

            }
        }
        return $commands;
    }

    private function isPhpFile(string $fileName): bool
    {
        return strlen($fileName) > 5 && ".php" === ($fileName[-4] . $fileName[-3] . $fileName[-2] . $fileName[-1]);
    }

    public function getCommandsFromCache(): array
    {
        $commandCachePath = $this->getCachePath();

        if (!file_exists($commandCachePath))
            throw new \Exception("Command cache file not found");

        return json_decode($this->files->get($commandCachePath));
    }

    public function getCachePath(): string
    {
        return app()->bootstrapPath() . '/cache/' . $this->cacheFile;
    }
}
