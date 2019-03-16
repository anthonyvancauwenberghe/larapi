<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 09.03.19
 * Time: 17:15.
 */

namespace Foundation\Generator\Abstracts;

use Foundation\Core\Larapi;
use Foundation\Core\Module;
use Foundation\Exceptions\Exception;
use Foundation\Generator\Support\InputOption;
use Foundation\Generator\Support\Stub;
use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileExistsException;
use Illuminate\Support\Str;
use ReflectionClass;
use Symfony\Component\Console\Input\InputArgument;

abstract class AbstractGeneratorCommand extends Command
{
    /**
     * The name of the generated resource.
     *
     * @var string
     */
    protected $generatorName;

    /**
     * The stub name.
     *
     * @var string
     */
    protected $stub;

    /**
     * The file path.
     *
     * @var string
     */
    protected $filePath;

    /**
     * The event that will fire when the file is created.
     *
     * @var string
     */
    protected $event;


    /**
     * The data that is inputted from the options.
     *
     * @var array
     */
    protected $optionData = [];

    /**
     * @return string
     */
    protected function getDestinationFilePath(): string
    {
        return $this->getModule()->getPath() . $this->filePath . '/' . $this->getFileName();
    }


    public function handle()
    {
        $path = str_replace('\\', '/', $this->getDestinationFilePath());

        if (file_exists($path) && !$this->isOverwriteable()) {
            $this->error("File : {$path} already exists.");
            throw new FileExistsException();
        }

        $this->handleOptions();

        $stub = new Stub($this->stubName(), array_merge($this->defaultStubOptions(), $this->stubOptions()));


        $this->beforeGeneration();

        if ($this->event === null)
            throw new Exception("No Generator event specified on " . static::class);

        event(new $this->event($path, $stub));
        $this->info("Created : {$path}");

        $this->afterGeneration();
    }

    /**
     * @return string
     */
    protected function getFileName()
    {
        return $this->getClassName() . '.php';
    }

    protected function isOverwriteable() :bool
    {
        $overWriteable = $this->option('overwrite');

        return $overWriteable ?? false;
    }

    protected function getModule(): Module
    {
        return once(function () {
            return Larapi::getModule($this->getModuleName());
        });
    }

    protected function getModuleName(): string
    {
        return once(function () {
            return Str::studly($this->askModuleName());
        });
    }

    private function askModuleName(): string
    {
        $moduleName = $this->argument('module') ?? $this->anticipate('For what module would you like to generate a ' . $this->getGeneratorName() . '.', Larapi::getModuleNames());

        if ($moduleName === null) {
            $this->error('module not specified');
            throw new \Exception('Name of module not specified.');
        }

        return $moduleName;
    }

    /**
     * Get class namespace.
     *
     *
     * @return string
     */
    public function getClassNamespace(): string
    {
        return $this->getModule()->getNamespace() . str_replace('/', '\\', $this->filePath);
    }


    protected function beforeGeneration(): void
    {
    }

    protected function afterGeneration(): void
    {
    }

    abstract protected function stubOptions(): array;

    protected final function defaultStubOptions(): array
    {
        return [
            "MODULE" => $this->getModuleName()
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    final protected function getOptions()
    {
        $options = $this->setOptions();
        $options[] = ['overwrite', null, InputOption::VALUE_NONE, 'Overwrite this file if it already exists?'];
        return $options;
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    final protected function getArguments()
    {
        return array_merge([
            ['module', InputArgument::OPTIONAL, 'The name of module will be used.'],
        ],
            $this->setArguments());
    }

    /**
     * Set the console command arguments.
     *
     * @return array
     */
    protected abstract function setArguments(): array;

    /**
     * Set the console command options.
     *
     * @return array
     */
    protected abstract function setOptions(): array;

    protected function getClassName(): string
    {
        return once(function () {
            return Str::studly($this->askClassName());
        });
    }

    private function askClassName(): string
    {
        $className = $this->argument('name') ?? $this->ask('Specify the name of the ' . $this->getGeneratorName() . '.');

        if ($className === null) {
            throw new \Exception('Name of ' . $this->getGeneratorName() . ' not set.');
        }

        return $className;
    }

    protected function getGeneratorName(): string
    {
        return $this->generatorName ?? 'class';
    }

    /**
     * Get the stub file name.
     * @return string
     */
    protected function stubName()
    {
        return $this->stub;
    }

    protected function handleOptions()
    {
        foreach ($this->getOptions() as $option) {
            $method = 'handle' . ucfirst(strtolower($option[0])) . 'Option';
            $originalInput = $this->getOriginalOptionInput();
            if (isset($originalInput[$option[0]])) {
                $this->optionData[$option[0]] = $originalInput[$option[0]];
            } else {
                $this->optionData[$option[0]] = method_exists($this, $method) ? $this->$method($option[1], $option[2], $option[3], $option[4]) : $this->option($option[0]);
            }
        }
    }

    protected function getOption(string $name)
    {
        return $this->optionData[$name];
    }

    private function buildInputOption($key, $shortcut, $type, $question, $default)
    {

        $originalOptions = $this->getOriginalOptionInput();
        if ($type === InputOption::VALUE_NONE) {
            return $this->option($key);
        } elseif ($type === InputOption::VALUE_OPTIONAL || $type === InputOption::VALUE_IS_BOOL) {
            if ($type !== InputOption::VALUE_IS_BOOL && ($input = $this->option($key)) !== null) {
                if (is_bool($default))
                    return (bool)$input;
                return $input;
            } else {
                if ($this->isShortcutBool($shortcut))
                    return $this->confirm($question, $default);
                else if (is_array($shortcut))
                    return $this->anticipate($question, $shortcut, $default);
                else
                    return $this->ask($question, $default);
            }

        }

        throw new Exception("input option not supported");
    }

    private function getOriginalOptionInput()
    {
        $reflection = new ReflectionClass($this->input);
        $property = $reflection->getProperty('options');
        $property->setAccessible(true);
        return $property->getValue($this->input);
    }

    private function isShortcutBool($shortcut)
    {
        return is_bool($shortcut) || (is_array($shortcut) && is_bool($shortcut[0]));
    }

    public function __call($method, $parameters)
    {
        $key = str_replace('get', '', $method);
        if (array_key_exists(strtolower($method), $this->optionData))
            return $this->optionData[$key];
    }
}
