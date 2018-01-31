<?php

namespace Endeavors\MaxMD\DirectUtil;

class RecipientCollection
{
    protected $recipients = [];

    public static function create(array $recipients, Contracts\IRecipient $recipient = null)
    {
        $instance = new static;

        if ( null === $recipient ) {
            foreach($recipients as $value) {
                $instance->recipients[] = new Recipient($value);
            }
        } else {
            foreach($recipients as $value) {
                $instance->recipients[] = new $recipient($value);
            }
        }

        return $instance;
    }

    public function add(Recipient $recipient)
    {
        $this->recipients[] = $recipient;
    }

    public function remove(Recipient $recipient)
    {
        foreach($this->recipients as $key => $toRemove) {
            if($toRemove === $recipient) {
                unset($this->recipients[$key]);
            }
        }
    }

    public function all()
    {
        return $this->recipients;
    }
}