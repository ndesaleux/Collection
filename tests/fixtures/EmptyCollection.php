<?php

namespace tests\fixtures;

use ndesaleux\Collection\CollectionInterface;
use ndesaleux\Collection\CollectionAbstract;

class EmptyCollection extends CollectionAbstract implements CollectionInterface
{
    protected $className = '';
}
