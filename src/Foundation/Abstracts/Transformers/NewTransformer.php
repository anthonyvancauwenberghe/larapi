<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 29.10.18
 * Time: 09:35.
 */

namespace Foundation\Abstracts\Transformers;

use Foundation\Contracts\Transformable;
use Illuminate\Http\Resources\Json\JsonResource;

class NewTransformer extends JsonResource implements Transformable
{
    use IncludesRelations;

    public $include = [];

    public $availableIncludes = [];

    public function __construct($resource)
    {
        parent::__construct($resource);
    }

    public static function resource($model): self
    {
        return new static($model);
    }

    public static function collection($resource)
    {
        return new AnonymousTransformerCollection($resource, static::class, call_class_function(static::class, 'compileRelations'));
    }

    public function serialize()
    {
        return json_decode(json_encode($this->jsonSerialize()), true);
    }
}
