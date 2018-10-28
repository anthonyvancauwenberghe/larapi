<?php

namespace Modules\Demo\Console;

use Foundation\Services\BootstrapRegistrarService;
use Illuminate\Console\Command;
use Modules\Demo\Jobs\AlterDemoDataJob;
use Modules\Demo\Jobs\SeedDemoDataJob;

class AlterDemoDataCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'demo:alter';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Alter Demo data.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        dispatch(new AlterDemoDataJob());
        $this->info('Alter Demo data job triggered.');
    }
}
