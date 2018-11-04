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

abstract class TransformerOld extends JsonResource implements Transformable
{
    protected $include = [];

    protected $available = [];

    /**
     * Resolve the resource to an array.
     *
     * @param \Illuminate\Http\Request|null $request
     *
     * @return array
     */
    public function resolve($request = null)
    {
        return array_merge(parent::resolve($request), $this->filter($this->getIncludedRelations($request)));
    }

    protected function parseRequestIncludeParameter($request)
    {
        if (isset($request->include) && is_string($request->include)) {
            return explode(',', $request->include);
        }

        return [];
    }

    protected function compileRelations(array $includedRequestRelations)
    {
        return array_unique(array_merge($this->include, array_intersect($this->available, $includedRequestRelations)));
    }

    public function getIncludedRelations($request)
    {
        $requestedRelations = $this->parseRequestIncludeParameter($request);
        $relations = [];
        foreach ($this->compileRelations($requestedRelations) as $relation) {
            if (is_string($relation) && method_exists($this, 'transform'.ucfirst(strtolower($relation)))) {
                $data = null;
                if ($this->resource !== null) {
                    $method = 'transform'.ucfirst(strtolower($relation));
                    $data = $this->$method($this->resource);
                }
                if ($data instanceof JsonResource) {
                    if ($data->resource === null) {
                        $data = null;
                    } else {
                        $data->jsonSerialize();
                    }
                }
                $relations[strtolower($relation)] = $data;
            } else {
                throw new \Exception('invalid relation or not relation_transform_method given in '.get_short_class_name(static::class));
            }
        }

        return $relations;
    }

    /**
     * @param $relations
     */
    public function include($relations)
    {
        if (is_string($relations)) {
            $relations = [$relations];
        }
        $this->include = array_unique(array_merge($this->include, $relations));

        return $this;
    }

    /**
     * @param $relations
     */
    public function available($relations)
    {
        if (is_string($relations)) {
            $relations = [$relations];
        }
        $this->available = array_unique(array_merge($this->available, $relations));

        return $this;
    }

    public static function resource($model): self
    {
        return new static($model);
    }

    public static function collection($resource)
    {
        return new AnonymousTransformerCollection($resource, static::class);
    }

    public function serialize()
    {
        return json_decode(json_encode($this->jsonSerialize()), true);
    }
}
