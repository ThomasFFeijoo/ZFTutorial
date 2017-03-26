<?php

namespace ZFT;

use Interop\Container\ContainerInterface;
use Zend\Db\Adapter\AdapterInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\Factory\InvokableFactory;
use ZFT\Migrations\Migrations;
use ZFT\User;

class Module implements ServiceProviderInterface {

    public function onBootstrap (MvcEvent $e) {
        $application = $e->getApplication();
        $sm = $application->getServiceManager();

        $application->getEventManager()->attach(MvcEvent::EVENT_DISPATCH, function(MvcEvent $e) use($sm)  {
            $router = $e->getRouteMatch();
            if(!($router->getParam('needsDatabase') === false)) {
                $adapter = $sm->get('dbcon');
                $migrations = new Migrations();
            }
        },100);
    }

    public function getServiceConfig()
    {
        return [
            'factories' => [
                User\MysqlDataMapper::class => InvokableFactory::class,
                User\MemoryIdentityMap::class => InvokableFactory::class,

                User\Repository::class => User\RepositoryFactory::class
            ],
            'aliases' => [
                'dbcon' => AdapterInterface::class
            ]
        ];
    }
}