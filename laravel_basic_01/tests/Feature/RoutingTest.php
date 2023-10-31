<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RoutingTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testBasicRouting(): void
    {

        $this->get("/test1")->assertStatus(200)->assertSeeText("test 1");
    }

    public function testRedirect(): void
    {

        $this->get("/youtube")->assertRedirect("/");
    }
    public function testFallback(): void
    {

        $this->get("/asdasdasdsadsa")->assertSeeText("redirect page");
    }

    public function testBladeTemplating() : void
    {
        $this->get("/")->assertSeeText("It is never too late to be what you might have been. - George Eliot");
    }

    public function testViewWithoutRoute() : void
    {
        $this->view("helloview", ['quotes' => 'mantap'])->assertSeeText("mantap");
    }


    //* Route Paramater
    public function testRouteParams()
    {
        $this->get('/products/1/items/2')->assertSeeText("Product 1");
    }

    public function testRouteParamsWithRegex(){
        $this->get('/categories/5')->assertSeeText("Categories : 5");
        $this->get('/categories/bambang')->assertSeeText("redirect");
    }


    public function testRouteOptional(){
        $this->get('/users/5')->assertSeeText("user 5");
        $this->get('/users')->assertSeeText("user 404");
    }


}
