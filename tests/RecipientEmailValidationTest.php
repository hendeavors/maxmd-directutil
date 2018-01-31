<?php

namespace Endeavors\MaxMD\DirectUtil\Tests;

use Endeavors\MaxMD\DirectUtil\Recipient;
use Endeavors\MaxMD\DirectUtil\Recipients;

class RecipientEmailValidationTest extends \Orchestra\Testbench\TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testRecipientWithValidEmailCanBeAdded()
    {
        // a recipient
        $recipient = new Recipient("bob@healthendeavors.com");
        // if we get here we have a valid recipient
        $this->assertEquals("bob@healthendeavors.com", $recipient);
    }
}