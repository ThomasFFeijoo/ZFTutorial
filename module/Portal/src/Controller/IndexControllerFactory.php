<?php

namespace Portal\Controller;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use ZFT\User;

class IndexControllerFactory implements FactoryInterface {

    /**
     * @param ContainerInterface $serviceManager
     * @param string $requestedName
     * @param array|null $options
     * @return IndexController
     */
    public function __invoke(ContainerInterface $serviceManager, $requestedName, array $options = null) {
        $repository = $serviceManager->get(User\Repository::class);
        return new IndexController($repository);
    }
}