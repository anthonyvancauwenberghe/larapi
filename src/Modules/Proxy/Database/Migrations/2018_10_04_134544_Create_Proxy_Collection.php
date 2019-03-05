<?php

class CreateProxyCollection extends \Modules\Mongo\Abstracts\MongoCollectionMigration
{
    protected $collection = 'proxies';

    public function migrate(\Jenssegers\Mongodb\Schema\Blueprint $collection)
    {
        $collection->index('user_id');
    }
}
