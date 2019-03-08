<?php

namespace Foundation\Generator\Commands;

use Foundation\Generator\Traits\QuickPaths;

class CommandMakeCommand extends \Nwidart\Modules\Commands\CommandMakeCommand
{
    use QuickPaths;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'larapi:make:command';

    /**
     * The stub name.
     *
     * @var string
     */
    protected $stub = 'command.stub';

    protected $fileName = '{module}Command.php';

    protected $filePath = '/Console';
}
