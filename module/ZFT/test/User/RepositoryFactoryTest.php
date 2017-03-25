<?php

namespace ZFTest\User;

use Zend\ServiceManager\ServiceManager;
use ZFT\User\MemoryIdentityMap;
use ZFT\User\Repository;
use ZFT\User\RepositoryFactory;
use ZFT\User;

class RepositoryFactoryTest extends \PHPUnit_Framework_TestCase {
    function testCanCreateUserRepository() {
        $sm = new ServiceManager();
        $sm->setFactory(MemoryIdentityMap::class, function() {
            return new class() implements User\IdentityMapInterface {};
        });

        $sm->setFactory(User\MysqlDataMapper::class, function() {
            return new class() implements  User\DataMapperInterface {};
        });

        $factory = new RepositoryFactory();
        $repository = $factory($sm, RepositoryFactory::class);

        $this->assertInstanceOf(Repository::class, $repository);
    }
}