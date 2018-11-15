<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 04.11.18
 * Time: 19:59.
 */

namespace Foundation\Abstracts\Transformers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Trait IncludesRelations.
 */
trait IncludesRelations
{

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
        $requestedRelations = $this->parseRequestIncludeParameter();
        $relations = [];
        foreach ($requestedRelations as $requestedRelation) {
            if (isset($this->available[$requestedRelation])) {
                $relations[$requestedRelation] = $this->available[$requestedRelation];
            }
        }
        $merge = array_merge($this->include, $relations);
        return array_unique($merge);
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

    protected function includeRelations()
    {
        $relations = [];
        if ($this->resource instanceof Model) {
            $relations = $this->compileRelations();
            foreach ($relations as $relation => $transformer) {
                $relationMethodName = 'transform' . ucfirst(strtolower($relation));
                if (method_exists($this, $relationMethodName)) {
                    $relations[$relation] = $this->$relationMethodName($this->resource->$relation);
                } else {
                    if ($this->resource->$relation instanceof Model) {
                        $relations[$relation] = $transformer::resource($this->whenLoaded($relation));
                    } else if ($this->resource->$relation instanceof Collection) {
                        $relations[$relation] = $transformer::collection($this->whenLoaded($relation));
                    }
                }

            }

        }
        return $relations;
    }
}
