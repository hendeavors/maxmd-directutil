<?php

namespace Endeavors\MaxMD\DirectUtil\Contracts;

interface IRecipient
{
    /**
     * @return bool
     */
    function isValid();

    /**
     * Get the raw item
     * @return string
     */
    function get();
}