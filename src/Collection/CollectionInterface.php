<?php

namespace ndesaleux\Collection;

interface CollectionInterface
{
    public function push($item);

    public function clean();

    public function has($item);
}
