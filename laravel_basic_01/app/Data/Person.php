<?php

namespace App\Data;

class Person
{
    public String $firstName;
    public String $lastName;
    //* inect foo on bar

    public function __construct($firstName, $lastName)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }
}