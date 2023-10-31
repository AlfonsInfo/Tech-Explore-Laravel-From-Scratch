<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use function PHPUnit\Framework\assertEquals;

class AccessConfigTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testAccess(): void
    {
        assertEquals("alfonsus",config("customconfig.author.first"));        
    }
}
