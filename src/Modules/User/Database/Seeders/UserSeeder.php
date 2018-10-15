<?php

namespace Modules\User\Database\Seeders;

use Auth0\Login\Contract\Auth0UserRepository;
use Illuminate\Database\Seeder;
use Modules\Auth0\Services\Auth0Service;
use Modules\User\Entities\User;

class UserSeeder extends Seeder
{
    /**
     * @var int
     */
    public $priority = 0;

    /**
     * @var Auth0Service
     */
    protected $service;

    /**
     * UserSeeder constructor.
     *
     * @param $service
     */
    public function __construct(Auth0UserRepository $service)
    {
        $this->service = $service;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->service->getPredefinedUser();
        factory(User::class, 5)->create();
    }
}
