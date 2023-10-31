<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ContohTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {

        //* Env bawaan dari framework laravel
        $envVariable = env ("TEST_ENV");
        self::assertEquals("ALFONS COBA AKSES VARIABLE DI ENV", $envVariable);
    }

    public function testDefaultEnv() : void
    {
        self::assertEquals("Laravel",env("APP_NAME"));
    }
}
