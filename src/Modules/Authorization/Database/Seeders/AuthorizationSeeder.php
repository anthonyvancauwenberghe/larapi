<?php

namespace Modules\Authorization\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Authorization\Attributes\PermissionAttributes;
use Modules\Authorization\Attributes\RolePermissions;
use Modules\Authorization\Contracts\AuthorizationContract;
use Modules\Authorization\Entities\Permission;
use Modules\Authorization\Entities\Role;
use Modules\Authorization\Services\AuthorizationService;

class AuthorizationSeeder extends Seeder
{
    public $priority = 0;

    /**
     * @var AuthorizationService
     */
    protected $service;

    /**
     * AuthorizationSeeder constructor.
     *
     * @param $service
     */
    public function __construct(AuthorizationContract $service)
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
        $this->service->clearPermissionCache();
        $this->createPermissions();
        $this->createRoles();
    }


    protected function createPermissions(): void
    {
        $permissions = get_class_constants(PermissionAttributes::class);
        $this->service->createPermissions(collect($permissions)->flatten()->toArray());
    }

    protected function createRoles(): void
    {
        $roles = get_class_constants(RolePermissions::class);
        foreach ($roles as $role => $permissions) {
            $this->service->createRole(strtolower($role), $permissions);
        }

        $this->createAdminRole();
    }

    protected function createAdminRole()
    {
        /* Create the admin role with all possible permissions */
        $permissions = get_class_constants(PermissionAttributes::class);
        $this->service->createRole(Role::ADMIN, collect($permissions)->flatten()->toArray());
    }
}
