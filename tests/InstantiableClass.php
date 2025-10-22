<?php

declare(strict_types=1);

namespace Zenigata\Utility\Test;

class InstantiableClass extends AbstractClass
{
    public string $foo;

    public function __construct(string $foo = 'bar')
    {
        $this->foo = $foo;
    }
}