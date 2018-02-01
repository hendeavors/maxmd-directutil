<?php

namespace Endeavors\MaxMD\DirectUtil;

/**
 * An adaptation to the RecipientCollection
 */
class ValidRecipientCollection
{
    private function __construct($collection)
    {
        $this->collection = $collection;
    }

    public static function create(array $recipients)
    {
        return new ValidRecipientCollection(RecipientCollection::create($recipients));
    }

    public function all()
    {
        return $this->collection->valid()->all();
    }

    public function toArray()
    {
        $arrayed = [];

        foreach($this->all() as $item) {
            $arrayed[] = $item->get();
        }

        return $arrayed;
    }
}