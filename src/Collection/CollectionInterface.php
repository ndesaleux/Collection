<?php

namespace ndesaleux\Collection;

interface CollectionInterface
{
    public function push($item, $strict = true);

    public function clean();

    public function has($item);
}
