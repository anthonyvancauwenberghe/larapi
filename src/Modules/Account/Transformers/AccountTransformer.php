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
use Modules\Account\Entities\Account;
use Modules\Machine\Transformers\MachineTransformer;
use Modules\User\Transformers\UserTransformer;

class AccountTransformer extends Transformer
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
    public function transformResource(Account $account)
    {
        $game = $this->game ?? null;
        switch ($game) {
            case 'OSRS':
                return $this->OSRSAccountToArray($account);
            case null:
                return;
            default:
                throw new Exception('Could not identity account game type');
        }
    }

    protected function OSRSAccountToArray(Account $account)
    {
        return [
            'id'                    => $account->id,
            'username'              => $account->username,
            'password'              => $account->password,
            'game'                  => $account->game,
            'bank_pin'              => $account->bank_pin,
            'location'              => $account->location,
            'ingame_name'           => $account->ingame_name,
            'skills'                => $account->skills,
            'membership_expires_at' => $account->membership_expires_at,
            'banned_at'             => $account->banned_at,
            'created_at'            => $account->created_at,
            'updated_at'            => $account->updated_at,
        ];
    }
}
