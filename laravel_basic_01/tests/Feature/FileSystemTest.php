<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FileSystemTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testStorage(): void
    {
        $fileSystem = Storage::disk("local");
        $fileSystem->put("file.txt", "Put Your Content Here");
        self::assertEquals("Put Your Content Here", $fileSystem->get("file.txt"));
    }
}
