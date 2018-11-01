<?php

class CreateAccountCollection extends \Modules\Mongo\Abstracts\MongoCollectionMigration
{
    protected $collection = 'accounts';

    public function migrate(\Jenssegers\Mongodb\Schema\Blueprint $collection)
    {
        $collection->index('user_id');
    }

}
