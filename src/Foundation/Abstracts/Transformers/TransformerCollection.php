<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 29.10.18
 * Time: 09:35
 */

namespace Foundation\Abstracts\Transformers;


use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TransformerCollection extends AnonymousResourceCollection
{
    public function serialize()
    {
        return json_decode(json_encode($this->jsonSerialize()),true);
    }
}
