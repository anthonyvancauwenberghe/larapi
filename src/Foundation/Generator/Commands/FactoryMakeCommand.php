<?php

namespace Foundation\Generator\Commands;

use Foundation\Generator\Traits\QuickPaths;

class FactoryMakeCommand extends \Nwidart\Modules\Commands\FactoryMakeCommand
{
    use QuickPaths;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'larapi:make:factory';

    /**
     * The stub name.
     *
     * @var string
     */
    protected $stub = 'factory';
}
