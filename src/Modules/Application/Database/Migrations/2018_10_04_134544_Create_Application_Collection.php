<?php

use Modules\Application\Entities\Application;

class CreateApplicationCollection extends \Modules\Mongo\Abstracts\MongoCollectionMigration
{
    protected $collection = 'applications';

    public function migrate(\Jenssegers\Mongodb\Schema\Blueprint $collection)
    {
        $collection->index(Application::TYPE);
        $collection->index(Application::USER_ID);
        $collection->index(Application::MACHINE_ID);
        $collection->index(Application::PROXY_ID);
        $collection->index(Application::SCHEDULE_ID);
        $collection->index(Application::SCRIPT_ID);
        $collection->index(Application::SCRIPT_CONFIG_ID);
    }
}
