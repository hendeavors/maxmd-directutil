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

    public function testNoTrustedRecipientsAreRecieved()
    {
        MaxMD::Login(env("MAXMD_APIUSERNAME"),env("MAXMD_APIPASSWORD"));

        User::freshLogin("freddie@healthendeavors.direct.eval.md", "smith");

        $recipients = Recipients::trusted([
            "adam@healthendeavors.com",
            "adam.david.rodriguez@gmail.com",
            "bad"
        ]);

        // untrusted recipients will be 2
        // bad is pre-validated
        $this->assertCount(0, $recipients);
    }

    public function testNoSingleUntrustedRecipientIsRecieved()
    {
        MaxMD::Login(env("MAXMD_APIUSERNAME"),env("MAXMD_APIPASSWORD"));

        User::freshLogin("freddie@healthendeavors.direct.eval.md", "smith");

        $recipients = Recipients::trusted([
            "adam@healthendeavors.com"
        ]);

        // trusted recipients will be 0
        $this->assertCount(0, $recipients);
    }

    public function testNoSingleTrustedRecipientIsRecieved()
    {
        MaxMD::Login(env("MAXMD_APIUSERNAME"),env("MAXMD_APIPASSWORD"));

        User::freshLogin("freddie@healthendeavors.direct.eval.md", "smith");

        $recipients = Recipients::unTrusted([
            "stevejones1231224@healthendeavors.direct.eval.md"
        ]);

        // untrusted recipients will be 0
        $this->assertCount(0, $recipients);
    }

    public function testNoSingleBadTrustedRecipientIsRecieved()
    {
        MaxMD::Login(env("MAXMD_APIUSERNAME"),env("MAXMD_APIPASSWORD"));

        User::freshLogin("freddie@healthendeavors.direct.eval.md", "smith");

        $recipients = Recipients::trusted([
            "bad"
        ]);

        $this->assertNotNull(Recipients::trustedErrorCode());

        $this->assertEquals(Recipients::trustedErrorCode(), 10);

        // invalid api argument or argument missing 
        $this->assertEquals(Recipients::trustedErrorMessage(), "recipients: missing");

        // trusted recipients will be 0
        $this->assertCount(0, $recipients);
    }

    public function testNoSingleBadUnTrustedRecipientIsRecieved()
    {
        MaxMD::Login(env("MAXMD_APIUSERNAME"),env("MAXMD_APIPASSWORD"));

        User::freshLogin("freddie@healthendeavors.direct.eval.md", "smith");

        $recipients = Recipients::unTrusted([
            "bad"
        ]);

        // untrusted recipients will be 0
        $this->assertCount(0, $recipients);
    }

    public function testEmptyTrustedRecipientsUsesPreviousArgumentsIfNotEmpty()
    {
        MaxMD::Login(env("MAXMD_APIUSERNAME"),env("MAXMD_APIPASSWORD"));

        User::freshLogin("freddie@healthendeavors.direct.eval.md", "smith");

        Recipients::trusted([
            "bad"
        ]);

        $recipients = Recipients::trusted();

        $this->assertNotNull(Recipients::trustedErrorCode());

        $this->assertEquals(Recipients::trustedErrorCode(), 10);

        // invalid api argument or argument missing 
        $this->assertEquals(Recipients::trustedErrorMessage(), "recipients: missing");

        // trusted recipients will be 0
        $this->assertCount(0, $recipients);

        Recipients::trusted([
            "freddie@healthendeavors.direct.eval.md",
            "stevejones1231224@healthendeavors.direct.eval.md",
            "adam@healthendeavors.com",
            "adam.david.rodriguez@gmail.com",
            "bad"
        ]);

        $recipients = Recipients::trusted();

        $this->assertCount(2, $recipients);
    }
}
