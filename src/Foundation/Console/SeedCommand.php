<?php

namespace Foundation\Console;

use Foundation\Exceptions\Exception;
use Foundation\Services\BootstrapRegistrarService;
use Illuminate\Database\ConnectionResolverInterface as Resolver;
use Illuminate\Database\Eloquent\Model;

class SeedCommand extends \Illuminate\Database\Console\Seeds\SeedCommand
{
    /**
     * The service that registers all module entities.
     *
     * @var BootstrapRegistrarService
     */
    protected $service;

    public function __construct(Resolver $resolver, BootstrapRegistrarService $service)
    {
        parent::__construct($resolver);
        $this->service = $service;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        if (!$this->confirmToProceed()) {
            return;
        }

        $this->resolver->setDefaultConnection($this->getDatabase());

        Model::unguarded(function () {
            $seeders = $this->getSeeders();

            $priorities = [];
            $prioritySeeders = [];
            $nonPrioritySeeders = [];
            foreach ($seeders as $seeder) {
                $priority = get_class_property($seeder, 'priority');
                if (!is_int($priority) && $priority !== null) {
                    throw new Exception('Priority on seeder must be integer');
                } elseif ($priority !== null && in_array($priority, $priorities)) {
                    throw new Exception("Duplicate priority on seeder $seeder with $prioritySeeders[$priority]");
                } elseif ($priority === null) {
                    $nonPrioritySeeders[] = $seeder;
                } else {
                    $priorities[] = $priority;
                    $prioritySeeders[$priority] = $seeder;
                }
            }
            ksort($prioritySeeders);
            $seeders = array_merge($prioritySeeders, $nonPrioritySeeders);

            foreach ($seeders as $seeder) {
                $seeder = $this->laravel->make($seeder);
                if (!isset($seeder->seed) || $seeder->seed === false) {
                    $seeder->__invoke();
                }
            }
        });

        $this->info('Database seeding completed successfully.');
    }

    protected function getSeeders(): array
    {
        $this->service = $this->laravel->make(BootstrapRegistrarService::class);

        return $this->service->getSeeders() ?? [];
    }
}
