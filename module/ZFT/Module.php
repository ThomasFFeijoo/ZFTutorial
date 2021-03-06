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

        $em = $application->getEventManager();
        $em->attach(MvcEvent::EVENT_DISPATCH, function(MvcEvent $e) use($sm, $em)  {
            $router = $e->getRouteMatch();
            if(!($router->getParam('needsDatabase') === false)) {
                $adapter = $sm->get('dbcon');
                $migrations = new Migrations($adapter);
                if ($migrations->needsUpdate()) {
                    $e->setName(MvcEvent::EVENT_DISPATCH_ERROR);
                    $e->setError("Database Needs Update");
                    $e->setParam('needsDatabaseUpdate', true);

                    $e->stopPropagation(true);
                    $em->triggerEvent($e);

                    return $e->getResult();
                }
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