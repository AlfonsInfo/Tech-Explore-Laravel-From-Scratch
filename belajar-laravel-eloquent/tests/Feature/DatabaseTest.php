<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
class DatabaseTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function testDatabaseConnection(): void
    {
        $connection = DB::connection('mysql');
        self::assertTrue($connection->getPdo() instanceof \PDO);
    }
}
