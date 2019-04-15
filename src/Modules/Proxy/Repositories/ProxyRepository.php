<?php


namespace Modules\Proxy\Repositories;

use Foundation\Abstracts\Repositories\Repository;
use Modules\Proxy\Contracts\ProxyRepositoryContract;
use Modules\Proxy\Entities\Proxy;

class ProxyRepository extends Repository implements ProxyRepositoryContract
{
    protected $eloquent = Proxy::class;
}