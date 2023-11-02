<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class LoggingTest extends TestCase
{
    public function testLogging(): void
    {
        Log::info("Hello Info");
        Log::warning("Hello warning");
        Log::error("Hello error");
        Log::critical("Hello critical");

        self::assertTrue(true);
    }
}
