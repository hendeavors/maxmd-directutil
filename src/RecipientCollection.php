<?php

namespace Endeavors\MaxMD\DirectUtil;

class RecipientCollection
{
    protected $recipients = [];

    protected $inValidRecipients = [];

    public static function create(array $recipients)
    {
        $instance = new static;

        foreach($recipients as $value) {
            $instance->add($instance->tryAdd($value));
        }

        return $instance;
    }

    public function add(Contracts\IRecipient $recipient)
    {
        $this->recipients[] = $recipient;
    }

    public function remove(Contracts\IRecipient $recipient)
    {
        foreach($this->recipients as $key => $toRemove) {
            if($toRemove === $recipient) {
                unset($this->recipients[$key]);
            }
        }
    }

    public function valid()
    {
        $valids = [];
        
        foreach($this->recipients as $recipient) {
            if ( $recipient instanceof Contracts\IRecipient ) {
                // check the instance implementation of isValid
                if($recipient->isValid()) {
                    $valids[] = $recipient;
                }
            }
        }
        
        $this->recipients = $valids;
        
        return $this;
    }

    protected function inValid()
    {
        $invalids = [];

        foreach($this->recipients as $recipient) {
            if ( $recipient instanceof InvalidRecipient ) {
                $invalids[] = $recipient;
            }
        }

        $this->recipients = $invalids;

        return $this;
    }

    public function all()
    {
        return $this->recipients;
    }

    public function toArray()
    {
        $arrayed = [];

        foreach($this->all() as $item) {
            $arrayed[] = $item->get();
        }

        return $arrayed;
    }

    protected function tryAdd($value)
    {
        // assume invalid
        $result = new InvalidRecipient($value);

        try {
            $result = new Recipient($value);
        } finally {
            return $result;
        }
    }
}