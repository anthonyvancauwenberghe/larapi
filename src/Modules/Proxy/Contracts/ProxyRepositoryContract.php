<?php


namespace Modules\Proxy\Contracts;


use Larapie\Repository\Contracts\RepositoryInterface;

interface ProxyRepositoryContract extends RepositoryInterface
{
    public function resolve($id);
}