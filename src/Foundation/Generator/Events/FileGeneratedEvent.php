<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 10.03.19
 * Time: 20:15
 */

namespace Foundation\Generator\Events;


use Foundation\Abstracts\Events\Event;

/**
 * Class FileGeneratedEvent
 * @package Foundation\Generator\Events
 */
class FileGeneratedEvent extends Event
{
    /**
     * @var string
     */
    public $filePath;

    /**
     * @var string
     */
    public $stubName;

    /**
     * @var array
     */
    public $stubOptions;

    /**
     * FileGeneratedEvent constructor.
     * @param string $filePath
     * @param string $stubName
     * @param array $stubOptions
     */
    public function __construct(string $filePath, string $stubName, array $stubOptions)
    {
        $this->filePath = $filePath;
        $this->stubName = $stubName;
        $this->stubOptions = $stubOptions;
    }

    /**
     * @return string
     */
    public function getFilePath(): string
    {
        return $this->filePath;
    }

    /**
     * @return string
     */
    public function getStubName(): string
    {
        return $this->stubName;
    }

    /**
     * @return array
     */
    public function getStubOptions(): array
    {
        return $this->stubOptions;
    }
}
