<?php

class CreateMachineCollection extends \Modules\Mongo\Abstracts\MongoCollectionMigration
{
    protected $collection = 'machines';

    public function migrate(\Jenssegers\Mongodb\Schema\Blueprint $collection)
    {
        $collection->index('user_id');
    }

}
