<?php

namespace ndesaleux\Collection;

class InvalidItem extends \Exception
{
    /**
     * @param string $classname
     * @param string $neededClassname
     *
     * @return InvalidItem
     */
    public static function fromClass($classname, $neededClassname)
    {
        return new self(sprintf('item given is instance of "%s", "%s" needed', $classname, $neededClassname));
    }
}
