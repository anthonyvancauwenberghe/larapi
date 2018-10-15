<?php

class CreateMachineMigration extends \Foundation\Abstracts\Migrations\MongoMigration
{
    protected $collection = 'machines';

    protected function migrate(\Jenssegers\Mongodb\Schema\Blueprint $collection)
    {
        $collection->index('user_id');
    }
}
