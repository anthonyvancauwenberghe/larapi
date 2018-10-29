<?php

namespace Foundation\Console;

use Foundation\Cache\ModelCacheOOP;
use Illuminate\Console\Command;

class ClearModelsCacheCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'cache:model:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear the model cache.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        ModelCacheOOP::clearAll();
        $this->info('Model cache has been reset!');
    }
}
