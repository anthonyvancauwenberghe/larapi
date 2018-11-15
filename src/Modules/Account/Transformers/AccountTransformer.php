<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 29.10.18
 * Time: 09:38.
 */

namespace Modules\Account\Transformers;

use Foundation\Abstracts\Transformers\Transformer;
use Foundation\Exceptions\Exception;
use Modules\Machine\Transformers\MachineTransformer;
use Modules\User\Transformers\UserTransformer;

class AccountTransformer extends Transformer
{
    public $available = [
        'user' => UserTransformer::class,
        'machine' => MachineTransformer::class,
    ];

    /**
     * Transform the resource into an array.
     *
     * @throws Exception
     *
     * @return array
     */
    public function transformResource()
    {
        $game = $this->game ?? null;
        switch ($game) {
            case 'OSRS':
                return $this->OSRSAccountToArray();
            case null:
                return null;
            default:
                throw new Exception('Could not identity account game type');
        }
    }

    protected function OSRSAccountToArray()
    {
        return [
            'id'                    => $this->id,
            'username'              => $this->username,
            'password'              => $this->password,
            'game'                  => $this->game,
            'bank_pin'              => $this->bank_pin,
            'location'              => $this->location,
            'ingame_name'           => $this->ingame_name,
            'skills'                => $this->skills,
            'membership_expires_at' => $this->membership_expires_at,
            'banned_at'             => $this->banned_at,
            'created_at'            => $this->created_at,
            'updated_at'            => $this->updated_at,
        ];
    }
}
