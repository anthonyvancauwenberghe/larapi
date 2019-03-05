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
use Modules\Machine\Transformers\MachineTransformer;
use Modules\User\Transformers\UserTransformer;

class ProxyTransformer extends Transformer
{
    public $available = [
        'user'    => UserTransformer::class,
        'machine' => MachineTransformer::class,
    ];

    /**
     * Transform the resource into an array.
     *
     * @throws Exception
     *
     * @return array
     */
    public function transformResource(Proxy $app)
    {
        $game = $this->game ?? null;
        switch ($game) {
            case 'OSRS':
                return $this->OSRSProxyToArray($app);
            case null:
                return;
            default:
                throw new Exception('Could not identity proxy game type');
        }
    }

    protected function OSRSProxyToArray(Proxy $proxy)
    {
        return [
            'id'                    => $proxy->id,
            'username'              => $proxy->username,
            'password'              => $proxy->password,
            'game'                  => $proxy->game,
            'bank_pin'              => $proxy->bank_pin,
            'location'              => $proxy->location,
            'ingame_name'           => $proxy->ingame_name,
            'skills'                => $proxy->skills,
            'membership_expires_at' => $proxy->membership_expires_at,
            'banned_at'             => $proxy->banned_at,
            'created_at'            => $proxy->created_at,
            'updated_at'            => $proxy->updated_at,
        ];
    }
}
