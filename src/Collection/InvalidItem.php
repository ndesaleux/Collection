<?php

namespace ndesaleux\Collection;

class InvalidItem extends \Exception
{
    /**
     * @param string $neededClassname
     *
     * @return InvalidItem
     */
    public static function fromWrongType($neededClassname)
    {
        return new self(sprintf('item given is not an instance of "%s"', $neededClassname));
    }
}
