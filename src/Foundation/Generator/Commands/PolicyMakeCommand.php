<?php

namespace Foundation\Generator\Commands;

use Foundation\Generator\Abstracts\AbstractGeneratorCommand;

class PolicyMakeCommand extends AbstractGeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'larapi:make:policy';

    /**
     * The name of the generated resource.
     *
     * @var string
     */
    protected $generatorName = 'policy';

    /**
     * The stub name.
     *
     * @var string
     */
    protected $stub = 'policy.stub';

    /**
     * The file path.
     *
     * @var string
     */
    protected $filePath = '/Policies';

    protected function stubOptions(): array
    {
        return [
            'NAMESPACE' => $this->getClassNamespace(),
            'CLASS' => $this->getClassName(),
        ];
    }
}
