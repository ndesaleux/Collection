<?php

namespace ndesaleux\Collection;

abstract class CollectionAbstract implements \Iterator, \Countable
{
    protected $className = null;
    protected $items = [];

    protected $position = 0;

    public function __construct()
    {
        if (trim($this->className) === '' || $this->className === null) {
            throw InvalidCollection::fromUnknownClass();
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

    public function has($item)
    {
        try {
            $this->isValid($item);
            do {
                if ($item == $this->current()) {
                    $this->rewind();
                    return true;
                }
                $this->next();
            } while ($this->valid());
        } catch (\Exception $e) {
        }
        return false;
    }

    private function isValid($item)
    {
        if ($item instanceof $this->className === false) {
            throw InvalidItem::fromClass(get_class($item));
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
