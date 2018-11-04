<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 04.11.18
 * Time: 19:59.
 */

namespace Foundation\Abstracts\Transformers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Trait IncludesRelations.
 */
trait IncludesRelations
{
    public function resolve($request = null)
    {
        return array_merge(parent::resolve($request), $this->includeRelations());
    }

    /**
     * @return array
     */
    protected function parseRequestIncludeParameter()
    {
        $request = request();
        if (isset($request->include) && is_string($request->include)) {
            return explode(',', $request->include);
        }

        return [];
    }

    /**
     * @return array
     */
    public function compileRelations()
    {
        return array_unique(array_merge($this->include, array_intersect($this->availableIncludes, $this->parseRequestIncludeParameter())));
    }

    /**
     * @param $relation
     *
     * @return $this
     */
    public function include($relation)
    {
        if (is_array($relation)) {
            $this->include = array_merge($this->include, $relation);
        } else {
            $this->include[] = $relation;
        }

        return $this;
    }

    /**
     * @throws \Exception
     *
     * @return array
     */
    protected function includeRelations()
    {
        $relations = [];
        foreach ($this->compileRelations() as $relation) {
            if (method_exists($this, 'transform'.ucfirst(strtolower($relation)))) {
                $data = null;
                if ($this->resource !== null && $this->resource instanceof Model) {
                    $method = 'transform'.ucfirst(strtolower($relation));
                    $data = $this->$method($this->resource->$relation);
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
}
