<?php

namespace ndesaleux\Collection;

class InvalidCollection extends \Exception
{
    /**
     * @return InvalidCollection
     */
    public static function fromUndefinedClass()
    {
        return new self('A collection must defined a classname');
    }

    /**
     * @param string $classname
     *
     * @return InvalidCollection
     */
    public static function fromUnexistingClass($classname)
    {
        return new self(sprintf('Classname "%s" is unknown from system', $classname));
    }
}
