<?php

namespace ndesaleux\Collection;

class InvalidCollection extends \Exception
{
    /**
     * @return InvalidCollection
     */
    public static function fromUnknownClass()
    {
        return new self('A classname must be given to Collection constructor');
    }

    /**
     * @param string $classname
     *
     * @return InvalidCollection
     */
    public static function fromUnexistingClass($classname)
    {
        return new self(sprintf('Classname "%s" is unknown', $classname));
    }
}
