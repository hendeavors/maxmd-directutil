<?php

namespace Endeavors\MaxMD\DirectUtil;

use Endeavors\Support\VO;

class Recipient implements Contracts\IRecipient
{
    /**
     * @var string recipient
     */
    protected $recipient;

    public function __construct($recipient)
    {
        $this->recipient = VO\EmailAddress::loose($recipient);
    }

    public function __toString()
    {
        return $this->recipient->get();
    }
}