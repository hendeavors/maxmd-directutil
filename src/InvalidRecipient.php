<?php

namespace Endeavors\MaxMD\DirectUtil;

use Endeavors\Support\VO;

class InvalidRecipient implements Contracts\IRecipient
{
    /**
     * @var string recipient
     */
    protected $recipient;

    public function __construct($recipient)
    {
        $this->recipient = VO\ModernString::create($recipient);
    }

    public function isValid()
    {
        return false;
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