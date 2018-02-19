<?php

namespace ndesaleux\Collection;

abstract class CollectionAbstract implements \Iterator, \Countable, CollectionInterface
{
    protected $className = null;
    protected $items = [];

    protected $position = 0;

    public function __construct()
    {
        if (trim($this->className) === '' || $this->className === null) {
            throw InvalidCollection::fromUndefinedClass();
        }
        if (!class_exists($this->className)) {
            throw InvalidCollection::fromUnexistingClass($this->className);
        }
    }

    public function add($item)
    {
        if ($this->isValid($item)) {
            $this->items[] = $item;
        }
    }

    public function clean()
    {
        $this->items = [];
    }

    /**
     * @param mixed $item
     *
     * @return bool
     */
    public function has($item)
    {
        try {
            $this->isValid($item);
            while ($this->valid()) {
                if ($item == $this->current()) {
                    return true;
                }
                $this->next();
            }
        } catch (InvalidItem $e) {
        }
        return false;
    }

    private function isValid($item)
    {
        if ($item instanceof $this->className === false) {
            throw InvalidItem::fromClass(get_class($item), $this->className);
        }
        return true;
    }

    public function current()
    {
        return $this->items[$this->position];
    }

    public function next()
    {
        ++$this->position;
    }

    public function rewind()
    {
        $this->position = 0;
    }

    public function key()
    {
        return $this->position;
    }

    public function valid()
    {
        return isset($this->items[$this->position]);
    }

    public function count()
    {
        return count($this->items);
    }
}
