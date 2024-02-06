<?php

namespace App\Data;

class Person
{
    var string $nama;

    public function __construct(string $nama)
    {
        $this->nama = $nama;
    }
}
