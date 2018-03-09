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
                ->implements(\IteratorAggregate::class)
                ->implements(\Countable::class)
                ->implements(CollectionInterface::class);
    }

    public function testNullCollectionThrowInvalidCollectionException()
    {
        $this
            ->exception(function() {
                $collection = new NullCollection();
                $collection->has(new \StdClass());
            })
            ->hasMessage('A collection must defined a classname');
    }

    public function testEmptyCollectionThrowInvalidCollectionException()
    {
        $this
            ->exception(function() {
                $collection = new EmptyCollection();
                $collection->has(new \StdClass());
            })
            ->hasMessage('A collection must defined a classname');
    }

    public function testUnexistingCollectionThrowInvalidCollectionException()
    {
        $this
            ->exception(function() {
                $collection = new UnexistingCollection();
                $collection->has(new \StdClass());
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
            ->if($collection->push($track1))
                ->integer($collection->count())
                    ->isEqualTo(1)
                ->boolean($collection->has($track1))
                    ->isTrue()
            ->if($collection->push($track2))
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
            ->and($collection->push($track1))
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
                $collection->push($track);
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
            $collection->push($track);
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
                $collection->push($track);
            })
                ->isInstanceOf(InvalidItem::class)
                    ->hasMessage(
                        sprintf('item given is not an instance of "%s"', Track::class)
                    );
    }

    public function testIteratorImplementation()
    {
        $this
            ->given($collection = new Album())
            ->and($track1 = new Track(uniqid('title'), uniqid('title')))
            ->and($track2 = new Track(uniqid('title'), uniqid('title')))
            ->and($track3 = new Track(uniqid('title'), uniqid('title')))
            ->and($collection->push($track1))
            ->and($collection->push($track2))
            ->and($collection->push($track3));

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

    public function testNewCollectionIsEmptyWhenGivenBadData()
    {
        $this
            ->given($array = [1,2,3])
            ->and($collection = new Album($array))
            ->integer(count($collection))
                ->isEqualTo(0);
    }

    public function testNewCollectionHasItemWhenGivenValidData()
    {
        $this
            ->given($track1 = new Track(uniqid('title'), uniqid('band')))
            ->and($track2 = new Track(uniqid('title'), uniqid('band')))
            ->and($array = [$track1, $track2])
            ->and($collection = new Album($array))
                ->integer(count($collection))
                    ->isEqualTo(count($array));
    }

    public function testPushInNotStrictMode()
    {
        $this
            ->given($track1 = new Track(uniqid('title'), uniqid('band')))
            ->and($collection = new Album())
            ->and($collection->push($track1, false))
                ->boolean($collection->has($track1))
                    ->isTrue()
                ->integer(count($collection))
                    ->isEqualTo(1)
            ->and($collection->push('toto', false))
                ->boolean($collection->has('toto'))
                    ->isFalse()
                ->integer(count($collection))
                    ->isEqualTo(1);
    }
}
