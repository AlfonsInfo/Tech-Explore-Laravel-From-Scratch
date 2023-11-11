<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;
class DatabaseTest extends TestCase
{
    public function testDatabaseConnection(): void
    {
        $connection = DB::connection('mysql');
        self::assertTrue($connection->getPdo() instanceof \PDO);
    }
}
