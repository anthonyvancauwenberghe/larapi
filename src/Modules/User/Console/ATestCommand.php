<?php

namespace Modules\User\Console;

use Illuminate\Console\Command;
use Modules\User\Entities\User;
use Modules\User\Events\UserRegisteredEvent;

class ATestCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'atest:cmd';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $user = User::get()->last();
        event(new UserRegisteredEvent($user));
        $this->info('working');
    }
}
