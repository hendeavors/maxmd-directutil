<?php

namespace Endeavors\MaxMD\DirectUtil\Tests;

use Endeavors\MaxMD\DirectUtil\Recipients;
use Endeavors\MaxMD\Api\Auth\MaxMD;
use Endeavors\MaxMD\Message\User;

class EmptyValidationTest extends TestCase
{
    public function testEmptyTrustedRecipientsGivesEmptyResponse()
    {
        MaxMD::Login(getenv("MAXMD_APIUSERNAME"),getenv("MAXMD_APIPASSWORD"));

        User::freshLogin("freddie@". getenv('MAXMD_DOMAIN'), "smith");

        Recipients::trusted();

        $this->assertNotNull(Recipients::trustedErrorCode());

        $this->assertEquals(Recipients::trustedErrorCode(), 10);
    }
}
