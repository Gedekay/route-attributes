<?php

namespace RouteAttributes\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class Patch
{
    public function __construct(
        public string $uri,
        public ?string $name = null
    ) {}
}   