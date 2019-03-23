<?php

namespace Modules\Script\Transformers;

use Foundation\Abstracts\Transformers\Transformer;
use Foundation\Exceptions\Exception;
use Modules\Script\Entities\Script;

class ScriptTransformer extends Transformer
{

    /**
     * Determines wich relations can be requested with the resource.
     *
     * @var array
     */
    public $available = [];

    /**
     * Transform the resource into an array.
     *
     * @throws Exception
     *
     * @return array
     */
    public function transformResource(Script $script)
    {
        return [
            'id' => $script->id,
            'created_at' => $script->created_at,
            'updated_at' => $script->updated_at,
        ];
    }
}
