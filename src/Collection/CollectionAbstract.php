<?php

namespace ndesaleux\Collection;

abstract class CollectionAbstract implements \IteratorAggregate, \Countable, CollectionInterface
{
    protected $className = null;
    protected $items = [];

    protected $position = 0;

    public function __construct(iterable $iterable = null, $strict = false)
    {
        $this->init();
        if ($iterable !== null) {
            foreach($iterable as $item)
            {
                $this->push($item, $strict);
            }
        }
    }

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

    public function push($item, $strict = true)
    {
        try {
            if ($this->hasGoodInstance($item)) {
                $this->items[] = $item;
            }
        } catch (InvalidItem $exception) {
            if ($strict === true) {
                throw $exception;
            }
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
        return in_array($item, $this->items, true);
    }

    private function hasGoodInstance($item)
    {
        if ($item instanceof $this->className === false) {
            throw InvalidItem::fromWrongType($this->className);
        }
        return true;
    }

    public function count()
    {
        return count($this->items);
    }

    public function getIterator()
    {
        foreach($this->items as $item) {
            yield $item;
        }
    }
}
