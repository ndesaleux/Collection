<?php

namespace tests\fixtures;

use ndesaleux\Collection\CollectionInterface;
use ndesaleux\Collection\CollectionAbstract;

class Album extends CollectionAbstract implements CollectionInterface
{
    protected $className = 'tests\\fixtures\\Track';
}
