<?php

class CreateUserMigration extends \Foundation\Abstracts\Migrations\MongoMigration
{
    protected $collection = 'users';

    protected function migrate(\Jenssegers\Mongodb\Schema\Blueprint $collection)
    {
    }
}
