<?php

namespace ZFTest;

use ZFT\User;

class UserTest extends \PHPUnit_Framework_TestCase {

    public function testeCanCreateUserObject() {
        $user = new User();

        $this->assertInstanceOf(User::class, $user);
    }
}