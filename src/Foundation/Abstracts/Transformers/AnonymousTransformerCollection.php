<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 29.10.18
 * Time: 09:35.
 */

namespace Foundation\Abstracts\Transformers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AnonymousTransformerCollection extends AnonymousResourceCollection
{
    protected $relations;

    public function __construct($resource, string $collects, array $relations = [])
    {
        $this->relations = $relations;
        $this->preloadRelations($resource, $relations);
        parent::__construct($resource, $collects);
    }

    private function preloadRelations($resource, $relations)
    {
        if (!empty($array) && $resource instanceof Model) {
            foreach ($relations as $relation) {
                if (is_string($relation) && !($resource->relationLoaded($relation))) {
                    $resource->load($relation);
                }
            }
        }
    }

    public function include()
    {
        return $this;
    }

    public function serialize()
    {
        return json_decode(json_encode($this->jsonSerialize()), true);
    }

    /**
     * Map the given collection resource into its individual resources.
     *
     * @param  mixed $resource
     * @return mixed
     */
    protected function collectResource($resource)
    {
        parent::collectResource($resource);
        $this->collection->transform(function ($item, $key) {
            if ($item instanceof NewTransformer)
                return $item->include($this->relations);
            return $item;
        });
    }
}
