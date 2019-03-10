<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 29.10.18
 * Time: 09:38.
 */

namespace Modules\Proxy\Transformers;

use Foundation\Abstracts\Transformers\Transformer;
use Foundation\Exceptions\Exception;
use Modules\Proxy\Entities\Proxy;
use Modules\User\Transformers\UserTransformer;

class ProxyTransformer extends Transformer
{
    public $available = [
        'user' => UserTransformer::class,
    ];

    /**
     * Transform the resource into an array.
     *
     * @throws Exception
     *
     * @return array
     */
    public function transformResource(Proxy $proxy)
    {
        return [
            'id' => $proxy->id,
            'alias' => $proxy->alias,
            'ip_address' => $proxy->ip_address,
            'port' => $proxy->port,
            'username' => $proxy->username,
            'password' => $proxy->password,
            'type' => $proxy->type,
            'online' => $proxy->online,
            'monitor' => $proxy->monitor,
            'anonimity_level' => $proxy->anonimity_level,
            'last_alive_at' => $proxy->last_alive_at,
            'last_checked_at' => $proxy->last_checked_at,
            'created_at' => $proxy->created_at,
            'updated_at' => $proxy->updated_at,
        ];
    }
}
