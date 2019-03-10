<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 09.03.19
 * Time: 21:51
 */

namespace Foundation\Core;

use Foundation\Abstracts\Events\Event;
use Foundation\Abstracts\Listeners\Listener;
use Foundation\Abstracts\Middleware\Middleware;
use Foundation\Abstracts\Observers\Observer;
use Foundation\Abstracts\Policies\Policy;
use Foundation\Abstracts\Services\Service;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Seeder;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Notifications\Notification;
use Illuminate\Routing\Controller;
use Illuminate\Support\ServiceProvider;

final class Module
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $path;

    /**
     * LarapiModule constructor.
     * @param $name
     */
    public function __construct(string $name, string $path)
    {
        $this->name = $name;
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }


    public function getListeners()
    {
        return new Resource('listeners', '/Listeners', $this, Listener::class);
    }

    public function getConfigs()
    {
        return new Resource('configs', '/Configs', $this);
    }

    public function getFactories(){
        return new Resource('factories', '/Listeners', $this);
    }

    public function getEvents()
    {
        return new Resource('events', '/Listeners', $this, Event::class);
    }

    public function getRoutes()
    {
        return new Resource('routes', '/Routes', $this);
    }

    public function getServices()
    {
        return new Resource('services', '/Listeners', $this, Service::class);
    }

    public function getPolicies()
    {
        return new Resource('policies', '/Policies', $this, Policy::class);
    }

    public function getTransformers(){
        return new Resource('transformers', '/Transformers', $this, JsonResource::class);
    }

    public function getProviders(){
        return new Resource('providers', '/Providers', $this, ServiceProvider::class);
    }

    public function getMigrations(){
        return new Resource('migrations', '/Database/Migrations', $this, Migration::class);
    }

    public function getModels(){
        return new Resource('models', '/Entities', $this, Model::class);
    }

    public function getObservers(){
        return new Resource('observers', '/Observers', $this, Observer::class);
    }

    public function getSeeders(){
        return new Resource('seeders', '/Database/Seeders', $this, Seeder::class);
    }

    public function getRequests(){
        return new Resource('requests', '/Http/Requests', $this, Request::class);
    }

    public function getMiddleWare(){
        return new Resource('middleware', '/Http/Middleware', $this, MiddleWare::class);
    }

    public function getTests(){
        return new Resource('tests', '/Tests', $this, TestCase::class);
    }

    public function getCommands(){
        return new Resource('commands', '/Console', $this, Command::class);
    }

    public function getNotifications(){
        return new Resource('notifications', '/Notifications', $this, Notification::class);
    }

    public function getControllers(){
        return new Resource('controllers', '/Http/Controllers', $this, Controller::class);
    }

    public function getNamespace(): string
    {
        return 'Modules' . '\\' . $this->getName();
    }

    public function getMainModel(){
        return $this->getModels()->getClassByName($this->getName());
    }
}
