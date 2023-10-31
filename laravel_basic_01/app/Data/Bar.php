<?php

namespace App\Data;

class Bar
{
    //* inect foo on bar
    public Foo $foo;

    public function __construct(Foo $foo)
    {
        $this->foo = $foo;
    }
    public function Bar() : String{
        return $this->foo->foo() . "bar";
    }
}