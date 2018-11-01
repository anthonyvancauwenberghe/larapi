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
        'user',
        'machine'
    ];

    public $include = [
        'machine',
        'user'
    ];

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     * @throws Exception
     */
    public function toArray($request)
    {
        $game = $this->game ?? null;
        switch ($game) {
            case "OSRS":
                return $this->OSRSAccountToArray();
            default:
                throw new Exception("Could not identity account game type");
        }
    }

    protected function OSRSAccountToArray()
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'password' => $this->password,
            'game' => $this->game,
            "bank_pin" => $this->bank_pin,
            "location" => $this->location,
            "ingame_name" => $this->ingame_name,
            "skills" => $this->skills,
            "membership_expires_at" => $this->membership_expires_at,
            'banned_at' => $this->banned_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    public function transformUser(Account $account)
    {
        return UserTransformer::resource($account->user);
    }

    public function transformMachine(Account $account)
    {
        return MachineTransformer::resource($account->machine);
    }
}
