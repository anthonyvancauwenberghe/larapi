<?php

use Modules\Mongo\Abstracts\MongoCollectionMigration as Migration;
use Jenssegers\Mongodb\Schema\Blueprint;

class CreateScriptCollection extends Migration
{
    protected $collection = 'scripts';

    public function migrate(Blueprint $schema)
    {

    }
}
