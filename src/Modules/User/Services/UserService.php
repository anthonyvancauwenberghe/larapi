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
        if ($id instanceof User) {
            return $id;
        }

        return User::find($id);
    }

    public function findByIdentityId($id): ?User
    {
        return User::cache()->findBy('identity_id', $id);
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

    public function setRoles($id, array $roles): void
    {
        if (!in_array(Role::USER, $roles)) {
            $roles[] = Role::USER;
        }
        $this->find($id)->syncRoles($roles);
    }
}
