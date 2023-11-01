<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class InputTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testInput(): void
    {
        $this->get("/input/hello?name=Alfons")->assertSeeText("Hello Alfons");
        $this->post("/input/hello", ["name" => "Alfons"])->assertSeeText("Hello Alfons");

    }

    public function testNestedInput(): void
    {
        $this->post("/input/hello/first", ["name" => ["first"=> "Alfons"]])->assertSeeText("Hello Alfons");
    }
    
    
    public function testReturnAllData() : void{
        $this->post("/input/hello/data", ["name" => ["first"=> "Alfons"]])->assertSeeText("Alfons");
    }
}
