<?php

namespace Portal\Controller;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Exception\ContainerModificationsNotAllowedException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;
use ZFT\Migrations\Migrations;
use ZFT\User;

class AdminControllerFactory implements FactoryInterface {

    /**
     * @param ContainerInterface $serviceManager
     * @param string $controllerName
     * @param array|null $options
     * @throws ServiceNotFoundException if unable to resolve the service.
     * @throws ServiceNotCreatedException if an exception is raised when
     *     creating a service.
     * @throws ContainerModificationsNotAllowedException if any other error occurs
     * @return AdminController
     */
    public function __invoke(ContainerInterface $serviceManager, $controllerName, array $options = null) {
        $dbcon = $serviceManager->get('dbcon');
        $migrations = new Migrations($dbcon);
        return new AdminController($migrations);
    }
}