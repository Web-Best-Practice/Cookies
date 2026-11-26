<?php

namespace WebBestPractice\Cookies\Events;

class Track
{
    /**
     * @param  array<string, mixed>  $params
     */
    public function __construct(
        public string $name,
        public array $params = [],
        public bool $force = false,
    ) {}
}
