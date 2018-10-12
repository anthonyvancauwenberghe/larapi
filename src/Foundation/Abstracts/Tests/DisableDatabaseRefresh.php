<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 11.10.18
 * Time: 14:25.
 */

namespace Foundation\Abstracts\Tests;

trait DisableDatabaseRefresh
{
    /**
     * Disables database reseeding after test.
     *
     * @return void
     */
    public function runDatabaseMigrations()
    {
    }
}
