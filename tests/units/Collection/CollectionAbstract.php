<?php
namespace tests\units\ndesaleux\Collection;

use ndesaleux\Collection\CollectionInterface;
use ndesaleux\Collection\InvalidItem;
use tests\fixtures\Album;
use tests\fixtures\EmptyCollection;
use tests\fixtures\NullCollection;
use tests\fixtures\Track;
use tests\fixtures\UnexistingCollection;

class CollectionAbstract extends \atoum
{
    public function testClassDefinition()
    {
        $this
            ->class(\ndesaleux\Collection\CollectionAbstract::class)
                ->isAbstract()
                ->implements(\Iterator::class)
                ->implements(\Countable::class)
                ->implements(CollectionInterface::class);
    }

    public function testNullCollectionThrowInvalidCollectionException()
    {
        $this
            ->exception(function() {
                $collection = new NullCollection();
            })
            ->hasMessage('A collection must defined a classname');
    }

    public function testEmptyCollectionThrowInvalidCollectionException()
    {
        $this
            ->exception(function() {
                $collection = new EmptyCollection();
            })
            ->hasMessage('A collection must defined a classname');
    }

    public function testUnexistingCollectionThrowInvalidCollectionException()
    {
        $this
            ->exception(function() {
                $collection = new UnexistingCollection();
            })
            ->hasMessage('Classname "\unexisting\class" is unknown from system');
    }

    public function testAddValidElementsAppendThosesElementsToCollection()
    {
        $this
            ->given($collection = new Album())
            ->and($track1 = new Track(uniqid('title'), uniqid('band')))
            ->and($track2 = new Track(uniqid('title'), uniqid('band')))
            ->integer($collection->count())
                ->isEqualTo(0)
            ->boolean($collection->has($track1))
                ->isFalse()
            ->if($collection->add($track1))
                ->integer($collection->count())
                    ->isEqualTo(1)
                ->boolean($collection->has($track1))
                    ->isTrue()
            ->if($collection->add($track2))
                ->integer($collection->count())
                    ->isEqualTo(2)
                ->boolean($collection->has($track1))
                    ->isTrue()
                ->boolean($collection->has($track2))
                    ->isTrue();
    }

    public function testHasElementReturnFalseWhenUsingABadElement()
    {
        $this
            ->given($collection = new Album())
            ->and($track1 = new Track(uniqid('title'), uniqid('band')))
            ->and($element = new \stdClass())
            ->and($collection->add($track1))
                ->boolean($collection->has($element))
                    ->isFalse();
    }

    public function testHasElementReturnFalseWhenUsingEmptyCollection()
    {
        $this
            ->given($collection = new Album())
            ->integer($collection->count())
                ->isEqualTo(0)
            ->boolean($collection->has(new Track(uniqid('title'), uniqid('band'))))
                ->isFalse();
    }

    public function testAddNElementAppendNElementToCollection()
    {
        $this
            ->given($collection = new Album())
            ->and($nbItem = rand(1,50))
            ->and($track = new Track(uniqid('title'), uniqid('band')));
            for ($i=0; $i<$nbItem; $i++) {
                $collection->add($track);
            }
            $this
                ->integer($collection->count())
                    ->isEqualTo($nbItem)
                ->integer(count($collection))
                    ->isEqualTo($nbItem);
    }

    public function testCleanClearCollection()
    {
        $this
            ->given($collection = new Album())
            ->and($nbItem = rand(1,50))
            ->and($track = new Track(uniqid('title'), uniqid('band')));
        for ($i=0; $i<$nbItem; $i++) {
            $collection->add($track);
        }
        $this
            ->integer(count($collection))
                ->isEqualTo($nbItem)
            ->if($collection->clean())
                ->integer(count($collection))
                    ->isEqualTo(0);
    }

    public function testAddInvalidElementThrowInvalidItemException()
    {
        $this
            ->given($collection = new Album())
            ->and($track = new \stdClass())
            ->exception(function() use ($collection, $track){
                $collection->add($track);
            })
                ->isInstanceOf(InvalidItem::class)
                    ->hasMessage(
                        sprintf('item given is instance of "%s", "%s" needed', \stdClass::class, Track::class)
                    );
    }

    public function testIteratorImplementation()
    {
        $this
            ->given($collection = new Album())
            ->and($track1 = new Track(uniqid('title'), uniqid('title')))
            ->and($track2 = new Track(uniqid('title'), uniqid('title')))
            ->and($track3 = new Track(uniqid('title'), uniqid('title')))
            ->and($collection->add($track1))
            ->and($collection->add($track2))
            ->and($collection->add($track3));

        foreach($collection as $i => $item) {
            $varName = 'track' . ($i + 1);
            $this
                ->object($item)
                    ->isIdenticalTo(${$varName});
        }
    }

    public function testNewCollectionIsEmpty()
    {
        $this
            ->given($collection = new Album())
                ->integer($collection->count())
                    ->isEqualTo(0);
    }
}
