<?php

namespace ZFT\User;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use ZFT\User;

class RepositoryFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $serviceManager, $requestedName, array $options = null) {
        $identityMap = $serviceManager->get(User\MemoryIdentityMap::class);
        $dataMapper = $serviceManager->get(User\MysqlDataMapper::class);
        return new User\Repository($identityMap, $dataMapper);
    }
}