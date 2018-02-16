<?php

namespace ndesaleux\Collection;

interface CollectionInterface
{
    public function add($item);

    public function clean();

    public function has($item);
}
