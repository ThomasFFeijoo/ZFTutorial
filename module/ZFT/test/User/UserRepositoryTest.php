<?php

namespace ZFTest\User;

use ZFT\User;

class UserRepositoryTest extends \PHPUnit_Framework_TestCase {

    public function testeCanCreateUserRepositoryObject() {
        $identityMapStub = new class() implements User\IdentityMapInterface {};
        $dataMapperStub = new class() implements  User\DataMapperInterface {};

        $repository = new User\Repository($identityMapStub, $dataMapperStub);

        $this->assertInstanceOf(User\Repository::class, $repository);
    }
}