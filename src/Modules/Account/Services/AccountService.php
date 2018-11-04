<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 04.10.18
 * Time: 16:17.
 */

namespace Modules\Account\Services;

use Carbon\Carbon;
use Modules\Account\Contracts\AccountServiceContract;
use Modules\Account\Entities\Account;
use Modules\Account\Events\AccountCreatedEvent;
use Modules\Account\Events\AccountUpdatedEvent;
use Modules\Machine\Entities\Machine;

class AccountService implements AccountServiceContract
{
    public function getByUserId($userId)
    {
        return Account::where('user_id', $userId)->get();
    }

    public function find($id): ?Account
    {
        if ($id instanceof Account) {
            return $id;
        }

        return Account::find($id);
    }

    public function update($id, $data): Account
    {
        $Account = $this->find($id);
        $Account->update($data);
        event(new AccountUpdatedEvent($Account));

        return $Account;
    }

    public function create($data): Account
    {
        $Account = Account::create($data);
        event(new AccountCreatedEvent($Account));

        return $Account;
    }

    public function delete($id): bool
    {
        return Account::destroy($id);
    }

    public function heartbeat($id, $data): void
    {
        $this->update($id, [
            'last_heartbeat' => Carbon::now(),
            'online'         => true,
        ]);
    }

    public function assignToMachine($id, ?Machine $machine)
    {
        return $this->update($id, ['machine_id' => $machine->id ?? null]);
    }

    public function unlinkFromMachine($id)
    {
        return $this->assignToMachine($id, null);
    }
}
