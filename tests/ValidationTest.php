<?php

namespace Endeavors\MaxMD\DirectUtil\Tests;

use Endeavors\MaxMD\DirectUtil\Recipients;
use Endeavors\MaxMD\Api\Auth\MaxMD;
use Endeavors\MaxMD\Message\User;

class ValidationTest extends \Orchestra\Testbench\TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testOnlyValidRecipientsAreRetrieved()
    {
        MaxMD::Login(env("MAXMD_APIUSERNAME"),env("MAXMD_APIPASSWORD"));

        User::freshLogin("freddie@healthendeavors.direct.eval.md", "smith");

        $recipients = Recipients::trusted([
            "freddie@healthendeavors.direct.eval.md",
            "stevejones1231224@healthendeavors.direct.eval.md",
            "adam@healthendeavors.com",
            "adam.david.rodriguez@gmail.com",
            "bad"
        ]);

        $this->assertCount(2, $recipients);

        foreach($recipients as $item) {
            // not a trusted address
            $this->assertNotEquals("adam@healthendeavors.com", $item);
            $this->assertNotEquals("adam.david.rodriguez@gmail.com", $item);
            $this->assertNotEquals("bad", $item);
        }
    }

    public function testOnlyInvalidRecipientsAreRetrieved()
    {
        MaxMD::Login(env("MAXMD_APIUSERNAME"),env("MAXMD_APIPASSWORD"));

        User::freshLogin("freddie@healthendeavors.direct.eval.md", "smith");

        $recipients = Recipients::unTrusted([
            "freddie@healthendeavors.direct.eval.md",
            "stevejones1231224@healthendeavors.direct.eval.md",
            "adam@healthendeavors.com",
            "adam.david.rodriguez@gmail.com",
            "bad"
        ]);
        
        // untrusted recipients will be 2
        // bad is pre-validated
        $this->assertCount(2, $recipients);

        foreach($recipients as $item) {
            // trusted address
            $this->assertNotEquals("freddie@healthendeavors.direct.eval.md", $item);
            $this->assertNotEquals("stevejones1231224@healthendeavors.direct.eval.md", $item);
        }
    }
}