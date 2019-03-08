<?php

class CreateScheduleCollection extends \Modules\Mongo\Abstracts\MongoCollectionMigration
{
    protected $collection = 'schedules';

    public function migrate(\Jenssegers\Mongodb\Schema\Blueprint $collection)
    {
        $collection->index('user_id');
    }
}
