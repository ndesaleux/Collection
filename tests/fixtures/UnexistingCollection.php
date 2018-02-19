<?php

namespace tests\fixtures;

use ndesaleux\Collection\CollectionInterface;
use ndesaleux\Collection\CollectionAbstract;

class UnexistingCollection extends CollectionAbstract implements CollectionInterface
{
    protected $className = '\\unexisting\\class';
}
