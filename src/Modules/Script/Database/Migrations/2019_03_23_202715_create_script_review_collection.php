<?php

use Modules\Mongo\Abstracts\MongoCollectionMigration as Migration;
use Jenssegers\Mongodb\Schema\Blueprint;

class CreateScriptReviewCollection extends Migration
{
    protected $collection = 'script_reviews';

    public function migrate(Blueprint $schema)
    {

    }
}
