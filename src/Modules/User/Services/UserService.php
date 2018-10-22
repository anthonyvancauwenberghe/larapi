<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 04.10.18
 * Time: 16:17.
 */

namespace Modules\User\Services;

use Modules\Authorization\Entities\Role;
use Modules\User\Contracts\UserServiceContract;
use Modules\User\Entities\User;

class UserService implements UserServiceContract
{
    public function all()
    {
        return User::all();
    }

    public function find($id): ?User
    {
        return User::find($id);
    }

    public function update($id, $data): ?User
    {
        $user = $this->find($id);
        $user->update($data);

        return $user;
    }

    public function create($data): User
    {
        $user = User::create($data);
        $user->assignRole(Role::USER);
        return $user;
    }

    public function delete($id): bool
    {
        return User::destroy($id);
    }

    public function newUser($data): User
    {
        $user = new User($data);
        $user->assignRole(Role::USER);
        return $user;
    }

    public function assignRole($id, $roles): void
    {
        $this->find($id)->assignRole($roles);
    }


}
