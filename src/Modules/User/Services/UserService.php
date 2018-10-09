<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 04.10.18
 * Time: 16:17.
 */

namespace Modules\User\Services;

use Cache;
use Modules\User\Contracts\UserServiceContract;
use Modules\User\Entities\User;

class UserService implements UserServiceContract
{
    public function find($id): ?User
    {
        return Cache::remember($this->getCacheName($id), $this->getCacheTime(), function () use ($id) {
            return User::find($id);
        });
    }

    public function update($id, $data): ?User
    {
        $user = $this->find($id);
        $user->update($data);
        \Cache::put($this->getCacheName($id), $user, $this->getCacheTime());

        return $user;
    }

    public function create($data): User
    {
        if (isset($data['_id'])) {
            $user = new User();
            $user->_id = $data['_id'];
            $user->fill($data);
            $user->save();
        } else {
            $user = User::create($data);
        }
        Cache::put($this->getCacheName($user->id), $user, $this->getCacheTime());

        return $user;
    }

    public function delete($id): bool
    {
        $deleted = User::delete($id);
        if ($deleted) {
            Cache::forget($this->getCacheName($id));
        }

        return $deleted;
    }

    protected function getCacheName($id)
    {
        return "user:$id";
    }

    protected function getCacheTime()
    {
        return 60;
    }
}
