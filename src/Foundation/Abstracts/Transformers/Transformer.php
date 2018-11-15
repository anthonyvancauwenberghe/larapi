<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 29.10.18
 * Time: 09:35.
 */

namespace Foundation\Abstracts\Transformers;

use Foundation\Contracts\Transformable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;

abstract class Transformer extends JsonResource implements Transformable
{
    use IncludesRelations;

    public $include = [];

    public $available = [];

    public function __construct($resource)
    {
        parent::__construct(self::loadRelations($resource));
    }

    public static function resource($model): self
    {
        return new static($model);
    }

    private static function loadRelations($resource)
    {
        if ($resource instanceof Model || $resource instanceof Collection) {
            $relations = call_class_function(static::class, 'compileRelations');
            $resource->loadMissing(array_keys($relations));
        }
        return $resource;
    }

    public static function collection($resource)
    {
        return new AnonymousTransformerCollection(self::loadRelations($resource), static::class);
    }

    public function serialize()
    {
        return json_decode(json_encode($this->jsonSerialize()), true);
    }

    public function toArray($request)
    {
        return array_merge($this->transformResource(), $this->includeRelations());
    }

    public function transformResource()
    {
        return [];
    }
}
