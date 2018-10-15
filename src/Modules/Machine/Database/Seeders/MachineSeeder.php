<?php

namespace Modules\Machine\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Machine\Entities\Machine;
use Modules\User\Entities\User;

class MachineSeeder extends Seeder
{
    public $priority = 1;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (User::all() as $user) {
            factory(Machine::class, 5)->create([
                'user_id' => $user->id,
            ]);
        }
    }
}
