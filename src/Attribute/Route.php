<?php

namespace App\Attribute;

use Attribute;

#[Attribute]
class Route
{
    public function __construct(
        public string $path,
        public string $method = 'GET'
    ) {
    }
}
