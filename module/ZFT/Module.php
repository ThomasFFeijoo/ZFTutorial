<?php

namespace ZFT;

use Interop\Container\ContainerInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\ServiceManager\Factory\InvokableFactory;
use ZFT\User;

class Module implements ServiceProviderInterface {

    public function getServiceConfig()
    {
        return [
            'factories' => [
                User\MysqlDataMapper::class => InvokableFactory::class,
                User\MemoryIdentityMap::class => InvokableFactory::class,
                User\Repository::class => User\RepositoryFactory::class
            ]
        ];
    }
}