<?php

namespace Endeavors\MaxMD\DirectUtil\Tests;

use Endeavors\MaxMD\DirectUtil\Recipient;
use Endeavors\MaxMD\DirectUtil\RecipientCollection;

class RecipientCollectionCreationTest extends \Orchestra\Testbench\TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

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

    public function testCollectionCanBeMadeFromCustomRecipient()
    {
        $custom = [
            "bob@healthendeavors.com"
        ];

        $recipients = RecipientCollection::create($custom, new CustomRecipient());

        foreach($recipients->all() as $recipient) {
            $this->assertInstanceOf(CustomRecipient::class, $recipient);
        }
    }

    public function testCollectionIgnoresExceptionsAndAddsValidEmails()
    {
        $emails = [
            "good@email.com",
            "reallybademail"
        ];

        $recipients = RecipientCollection::create($emails);
    }
}

class CustomRecipient implements \Endeavors\MaxMD\DirectUtil\Contracts\IRecipient {}