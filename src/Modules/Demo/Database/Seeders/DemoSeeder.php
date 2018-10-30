<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 28.10.18
 * Time: 16:15.
 */

namespace Modules\Demo\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Auth0\Contracts\Auth0ServiceContract;
use Modules\Authorization\Entities\Role;
use Modules\Machine\Entities\Machine;

class DemoSeeder extends Seeder
{
    public $enabled = false;

    public $service;

    /**
     * DemoSeeder constructor.
     *
     * @param bool $seed
     */
    public function __construct(Auth0ServiceContract $auth0Service)
    {
        $this->service = $auth0Service;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = $this->seedUser();
        $machines = $this->seedMachines($user);
    }

    protected function seedUser()
    {
        $user = $this->service->getTestUser();
        $user->assignRole(Role::ADMIN);
        return $user;
    }

    protected function seedMachines($user)
    {
        $machines = factory(Machine::class, 5)->create([
            'user_id' => $user->id,
        ]);

        return $machines;
    }
}
