<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 08.03.19
 * Time: 18:45
 */

namespace Foundation\Generator\Traits;


use Foundation\Generator\Support\Stub;

trait QuickPaths
{
    /**
     * @return mixed
     */
    protected function getTemplateContents()
    {
        return (new Stub($this->stub))->render();
    }

    /**
     * @return mixed
     */
    protected function getDestinationFilePath()
    {
        return get_module_path($this->argument('name')) . $this->filePath . '/' . $this->getFileName();
    }

    /**
     * @return string
     */
    private function getFileName()
    {
        return str_replace('{module}', ucfirst($this->argument('name')), $this->fileName);
    }
}
