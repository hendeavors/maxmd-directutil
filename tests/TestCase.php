<?php

namespace Endeavors\MaxMD\DirectUtil\Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;
use Endeavors\MaxMD\Support\Domains;
use Endeavors\MaxMD\Message\User;

class TestCase extends BaseTestCase
{
    public function setUp()
    {
        parent::setUp();
        Domains::setDevelopmentMode(true);
    }

    protected function freshLogin($user, $pass)
    {
        User::logout();
        User::login($user, $pass);
    }
}
