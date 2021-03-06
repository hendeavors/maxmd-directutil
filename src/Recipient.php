<?php

namespace Endeavors\MaxMD\DirectUtil;

use Endeavors\Support\VO;

/**
 * Creates a recipient where the email address is valid syntactically
 */
class Recipient implements Contracts\IRecipient
{
    /**
     * @var string recipient
     * @throws \RuntimeException
     */
    protected $recipient;

    public function __construct($recipient)
    {
        $this->recipient = VO\EmailAddress::loose($recipient);
    }

    public function isValid()
    {
        return true;
    }

    public function get()
    {
        return $this->recipient->get();
    }

    public function __toString()
    {
        return $this->get();
    }
}