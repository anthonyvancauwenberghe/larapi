<?php

namespace Foundation\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Nwidart\Modules\Module;

class ConsoleCacheCommand extends Command
{
    protected $files;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'commands:cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Returns a list of all available command classes in the Modules namespace.';

    protected $moduleCommandDirectoryNames = [
        'console',
        'commands'
    ];

    /**
     * Create a new route command instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem $files
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        /*$commands = json_encode($this->getModuleCommands());

        $this->files->put(
            $this->laravel->bootstrapPath().'/cache/commands.json', $commands
        );*/

      //  $this->info('Commands cached successfully!');

    }

    private function getModuleCommands(): array
    {
        $commands = [];

        foreach ($this->getModules() as $module) {
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


    /**
     * @return Module[]
     */
    private function getModules()
    {
        return \Module::all();
    }

    private function isPhpFile(string $fileName): bool
    {
        return strlen($fileName) > 5 && ".php" === ($fileName[-4] . $fileName[-3] . $fileName[-2] . $fileName[-1]);
    }
}
