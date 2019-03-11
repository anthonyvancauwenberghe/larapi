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
final class FileGeneratedEvent extends Event
{
    /**
     * @var string
     */
    protected $filePath;

    /**
     * @var string
     */
    protected $stubName;

    /**
     * @var array
     */
    protected $stubOptions;

    /**
     * @var string
     */
    protected $generationClass;

    /**
     * FileGeneratedEvent constructor.
     * @param string $filePath
     * @param string $stubName
     * @param array $stubOptions
     * @param string $generationClass
     */
    public function __construct(string $filePath, string $stubName, array $stubOptions, string $generationClass)
    {
        $this->filePath = $filePath;
        $this->stubName = $stubName;
        $this->stubOptions = $stubOptions;
        $this->generationClass = $generationClass;
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

    /**
     * @return string
     */
    public function getGenerationClass(): string
    {
        return $this->generationClass;
    }


}
