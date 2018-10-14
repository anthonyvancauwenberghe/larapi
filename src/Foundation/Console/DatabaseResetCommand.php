<?php

namespace Foundation\Console;

use Artisan;
use Foundation\Services\BootstrapRegistrarService;
use Illuminate\Console\Command;

class DatabaseResetCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'db:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all tables/collections and reseed.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        Artisan::call('migrate:fresh');
        Artisan::call('cache:clear');
        Artisan::call('db:seed');
        $this->info('Database has been reset!');
    }
}
