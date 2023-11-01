<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class UploadTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testUpload(): void
    {
        $image = UploadedFile::fake()->image("alfons.png");

        $this->post('file/upload',['picture' => $image])->assertSeeText("alfons.png");

    }
}
