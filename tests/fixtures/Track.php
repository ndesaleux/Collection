<?php

namespace tests\fixtures;

class Track
{
    protected $title;

    protected $band;

    public function __construct($title, $band)
    {
        $this->title = $title;
        $this->band  = $band;
    }
}
