<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 29.10.18
 * Time: 09:35.
 */

namespace Foundation\Abstracts\Transformers;

use Foundation\Contracts\Transformable;
use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TransformerCollection extends AnonymousResourceCollection
{
    protected $relations = [];

    public function __construct($resource, string $collects)
    {
        $this->relations = $this->findIncludedRelations(Container::getInstance()->make('request'), $resource, $collects);
        $resource = $this->preloadModelRelations($resource);
        parent::__construct($resource, $collects);
    }

    /**
     * @param $request
     * @throws \Exception
     */
    protected function findIncludedRelations($request, $resource, $collects)
    {
        if (class_implements_interface($collects,Transformable::class)) {
            $transformer = $collects::resource($resource->first());
            $relations = $transformer->getIncludedRelations($request);
        }
        return $relations;
    }

    protected function preloadModelRelations($resource)
    {
        if ($resource instanceof Model) {
            foreach ($this->relations as $relation) {
                $resource->load($relation);
            }
        }
        return $resource;
    }

    public function toArray($request)
    {
        $collection = $this->collection->map(function ($item) {
            if ($item instanceof Transformer)
                return $item->include($this->relations);
            else
                return $item;
        });
        return $collection->map->resolve($request)->all();
    }


    public function serialize()
    {
        return json_decode(json_encode($this->jsonSerialize()), true);
    }
}
