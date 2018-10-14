<?php

namespace Modules\User\Console;

use Auth0\Login\Contract\Auth0UserRepository;
use Illuminate\Console\Command;
use Modules\User\Entities\User;
use Modules\User\Notifications\UserRegisteredNotification;
use Modules\User\Services\UserService;

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
        $user->notify(new UserRegisteredNotification($user));
        $this->info('working');
    }
}
