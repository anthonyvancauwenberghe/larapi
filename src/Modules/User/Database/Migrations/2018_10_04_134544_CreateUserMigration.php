<?php

class CreateUserMigration extends \Foundation\Abstracts\Migrations\MongoMigration
{
    protected $collection = 'users';

    protected function migrate(\Jenssegers\Mongodb\Schema\Blueprint $collection)
    {
        $collection->unique('auth0_id');
        $collection->unique('api_token');
    }
}
