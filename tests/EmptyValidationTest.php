<?php

namespace Endeavors\MaxMD\DirectUtil\Tests;

use Endeavors\MaxMD\DirectUtil\Recipients;
use Endeavors\MaxMD\Api\Auth\MaxMD;
use Endeavors\MaxMD\Message\User;

class EmptyValidationTest extends \Orchestra\Testbench\TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testEmptyTrustedRecipientsGivesEmptyResponse()
    {
        MaxMD::Login(env("MAXMD_APIUSERNAME"),env("MAXMD_APIPASSWORD"));

        User::freshLogin("freddie@healthendeavors.direct.eval.md", "smith");

        Recipients::trusted();

        $this->assertNotNull(Recipients::trustedErrorCode());

        $this->assertEquals(Recipients::trustedErrorCode(), 10);
    }
}
