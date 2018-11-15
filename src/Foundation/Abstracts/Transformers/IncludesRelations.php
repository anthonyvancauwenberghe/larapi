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
}
