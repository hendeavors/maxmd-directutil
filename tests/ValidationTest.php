<?php

namespace Endeavors\MaxMD\DirectUtil\Tests;

use Endeavors\MaxMD\DirectUtil\Recipients;
use Endeavors\MaxMD\Api\Auth\MaxMD;
use Endeavors\MaxMD\Message\User;

class ValidationTest extends TestCase
{
    public function testOnlyValidRecipientsAreRetrieved()
    {
        MaxMD::Login(getenv("MAXMD_APIUSERNAME"),getenv("MAXMD_APIPASSWORD"));

        User::freshLogin("freddie@" . getenv('MAXMD_DOMAIN'), "smith");

        $recipients = Recipients::trusted([
            "freddie@" . getenv('MAXMD_DOMAIN'),
            "stevejones1231224@" . getenv('MAXMD_DOMAIN'),
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
        MaxMD::Login(getenv("MAXMD_APIUSERNAME"),getenv("MAXMD_APIPASSWORD"));

        User::freshLogin("freddie@" . getenv('MAXMD_DOMAIN'), "smith");

        $recipients = Recipients::unTrusted([
            "freddie@" . getenv('MAXMD_DOMAIN'),
            "stevejones1231224@" . getenv('MAXMD_DOMAIN'),
            "adam@healthendeavors.com",
            "adam.david.rodriguez@gmail.com",
            "bad"
        ]);

        // untrusted recipients will be 2
        // bad is pre-validated
        $this->assertCount(2, $recipients);

        foreach($recipients as $item) {
            // trusted address
            $this->assertNotEquals("freddie@" . getenv('MAXMD_DOMAIN'), $item);
            $this->assertNotEquals("stevejones1231224@" . getenv('MAXMD_DOMAIN'), $item);
        }
    }

    public function testNoTrustedRecipientsAreRecieved()
    {
        MaxMD::Login(getenv("MAXMD_APIUSERNAME"),getenv("MAXMD_APIPASSWORD"));

        User::freshLogin("freddie@" . getenv('MAXMD_DOMAIN'), "smith");

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
        MaxMD::Login(getenv("MAXMD_APIUSERNAME"),getenv("MAXMD_APIPASSWORD"));

        User::freshLogin("freddie@" . getenv('MAXMD_DOMAIN'), "smith");

        $recipients = Recipients::trusted([
            "adam@healthendeavors.com"
        ]);

        // trusted recipients will be 0
        $this->assertCount(0, $recipients);
    }

    public function testNoSingleTrustedRecipientIsRecieved()
    {
        MaxMD::Login(getenv("MAXMD_APIUSERNAME"),getenv("MAXMD_APIPASSWORD"));

        User::freshLogin("freddie@". getenv('MAXMD_DOMAIN'), "smith");

        $recipients = Recipients::unTrusted([
            "stevejones1231224@" . getenv('MAXMD_DOMAIN')
        ]);

        // stevejones is now untrusted(12/5/2018) recipients will be 1
        $this->assertCount(1, $recipients);
    }

    public function testNoSingleBadTrustedRecipientIsRecieved()
    {
        MaxMD::Login(getenv("MAXMD_APIUSERNAME"),getenv("MAXMD_APIPASSWORD"));

        User::freshLogin("freddie@". getenv('MAXMD_DOMAIN'), "smith");

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
        MaxMD::Login(getenv("MAXMD_APIUSERNAME"),getenv("MAXMD_APIPASSWORD"));

        User::freshLogin("freddie@". getenv('MAXMD_DOMAIN'), "smith");

        $recipients = Recipients::unTrusted([
            "bad"
        ]);

        // untrusted recipients will be 0
        $this->assertCount(0, $recipients);
    }

    public function testEmptyTrustedRecipientsUsesPreviousArgumentsIfNotEmpty()
    {
        MaxMD::Login(getenv("MAXMD_APIUSERNAME"),getenv("MAXMD_APIPASSWORD"));

        User::freshLogin("freddie@". getenv('MAXMD_DOMAIN'), "smith");

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
            "freddie@". getenv('MAXMD_DOMAIN'),
            "stevejones1231224@" . getenv('MAXMD_DOMAIN'),
            "adam@healthendeavors.com",
            "adam.david.rodriguez@gmail.com",
            "bad"
        ]);

        $recipients = Recipients::trusted();

        $this->assertCount(2, $recipients);
    }
}
