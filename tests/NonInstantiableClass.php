<?php

declare(strict_types=1);

namespace Zenigata\Utility\Test;

class NonInstantiableClass extends AbstractClass
{
    public string $foo;

    public function __construct(string $foo)
    {
        $this->foo = $foo;
    }
}