<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 03.10.18
 * Time: 20:57.
 */

namespace Modules\Mongo\Abstracts;


/**
 * Class MongoMigration.
 *
 */
abstract class MongoMigration extends \Illuminate\Database\Migrations\Migration
{
    protected $connection = 'mongodb';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public abstract function up();

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public abstract function down();
}
