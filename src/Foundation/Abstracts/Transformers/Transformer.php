<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 29.10.18
 * Time: 09:35
 */

namespace Foundation\Abstracts\Transformers;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\JsonResource;

abstract class Transformer extends JsonResource
{
    public $relations = [

    ];

    /**
     * Resolve the resource to an array.
     *
     * @param  \Illuminate\Http\Request|null $request
     * @return array
     */
    public function resolve($request = null)
    {
        return array_merge(parent::resolve($request),$this->filter($this->includeRelations()));
    }

    protected function includeRelations()
    {
        $relations = [];
        foreach ($this->relations as $relation) {
            if (is_string($relation) && method_exists(static::class, 'transform' . ucfirst(strtolower($relation)))) {
                $method = 'transform' . ucfirst(strtolower($relation));
                $data = $this->$method($this->resource);
                if ($data instanceof JsonResource) {
                    $data->jsonSerialize();
                }
                $relations[strtolower($relation)] = $data;
            } else {
                throw new \Exception("invalid relation or not relation_transform_method given in " . get_short_class_name(static::class));
            }

        }
        return $relations;
    }

    public static function resource($model)
    {
        return new static($model);
    }

    public static function collection($resource)
    {
        return new TransformerCollection($resource, get_called_class());
    }

    public function serialize()
    {
        return json_decode(json_encode($this->jsonSerialize()), true);
    }
}
