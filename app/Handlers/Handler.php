<?php

namespace App\Handlers;

class Handler 
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }
}