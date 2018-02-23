<?php

namespace ndesaleux\Collection;

abstract class CollectionAbstract implements \Iterator, \Countable, CollectionInterface
{
    protected $className = null;
    protected $items = [];

    protected $position = 0;

    protected $isInit = false;

    private function init()
    {
        if (trim($this->className) === '' || $this->className === null) {
            throw InvalidCollection::fromUndefinedClass();
        }
        if (!class_exists($this->className)) {
            throw InvalidCollection::fromUnexistingClass($this->className);
        }
        $this->isInit = true;
    }

    public function push($item)
    {
        if ($this->hasGoodInstance($item)) {
            $this->items[] = $item;
        }
    }

    public function clean()
    {
        $this->items = [];
    }

    /**
     * @param $item
     *
     * @return bool
     *
     * @throws InvalidCollection
     */
    public function has($item)
    {
        try {
            $this->hasGoodInstance($item);
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

    private function hasGoodInstance($item)
    {
        if ($this->isInit === false) {
            $this->init();
        }
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
