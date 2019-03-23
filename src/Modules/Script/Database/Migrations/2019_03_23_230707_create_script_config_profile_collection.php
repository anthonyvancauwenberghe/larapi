<?php

use Modules\Mongo\Abstracts\MongoCollectionMigration as Migration;
use Jenssegers\Mongodb\Schema\Blueprint;

class CreateScriptConfigProfileCollection extends Migration
{
    protected $collection = 'script_config_profiles';

    public function migrate(Blueprint $schema)
    {

    }
}
