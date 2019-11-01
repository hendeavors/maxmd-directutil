<?php

namespace Endeavors\MaxMD\DirectUtil\Tests;

use Endeavors\MaxMD\DirectUtil\Recipient;
use Endeavors\MaxMD\DirectUtil\RecipientCollection;
use Endeavors\MaxMD\DirectUtil\ValidRecipientCollection;

class RecipientCollectionCreationTest extends TestCase
{
    public function testCollectionCanBeMadeFromDefaultRecipient()
    {
        $default = [
            "bob@healthendeavors.com"
        ];

        $recipients = RecipientCollection::create($default);

        foreach($recipients->all() as $recipient) {
            $this->assertInstanceOf(Recipient::class, $recipient);
        }
    }

    public function testCollectionIgnoresExceptionsAndAddsValidEmails()
    {
        $emails = [
            "good@email.com",
            "reallybademail"
        ];

        $recipients = ValidRecipientCollection::create($emails);

        foreach($recipients->all() as $recipient) {
            $this->assertEquals($emails[0], $recipient);
        }

        foreach($recipients->all() as $recipient) {
            $this->assertNotEquals($emails[1], $recipient);
        }
    }

    public function testCollectionWithAllBadEmail()
    {
        $emails = [
            "onereallybademail",
            "reallybademail"
        ];

        $recipients = ValidRecipientCollection::create($emails);

        $this->assertCount(0, $recipients->all());
    }
}
