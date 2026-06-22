<?php

namespace RouteAttributes\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class Post
{
    public function __construct(
        public string $uri,
        public ?string $name = null
    ) {}
}