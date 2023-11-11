<?php

namespace Tests\Feature;

use Database\Seeders\CategorySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Category;

class ResourceTest extends TestCase
{
    public function testResource(): void
    {
        $this->seed(CategorySeeder::class);
        $category = Category::first();

        $this->get("/api/categories/$category->id")
            ->assertStatus(200)
            ->assertJson(
                ['Category' =>[
                    'id' => $category->id,
                    'name' => $category->name,
                    'created_at' => $category->created_at->toJson(),
                    'updated_at' => $category->updated_at->toJson(),
                ]]
                );
    }


    public function testResourceCollection(): void
    {
        $this->seed(CategorySeeder::class);
        $category = Category::all();

        $this->get("/api/categories")
            ->assertStatus(200)
            ->assertJson(
                [
                    'data' =>
                    [
                        [
                            'id' => $category[0]->id,
                            'name' => $category[0]->name,
                            'created_at' => $category[0]->created_at->toJson(),
                            'updated_at' => $category[0]->updated_at->toJson(),
                        ],
                        [
                            'id' => $category[1]->id,
                            'name' => $category[1]->name,
                            'created_at' => $category[1]->created_at->toJson(),
                            'updated_at' => $category[1]->updated_at->toJson(),
                        ],
                    ]
                ]
                );
    }


    public function testCustomCollection(): void
    {
        $this->seed(CategorySeeder::class);
        $category = Category::all();

        $this->get("/api/custom-categories")
            ->assertStatus(200)
            ->assertJson(
                [
                    'data' =>
                    [
                        [
                            'id' => $category[0]->id,
                            'name' => $category[0]->name,
                            // 'created_at' => $category[0]->created_at->toJson(),
                            // 'updated_at' => $category[0]->updated_at->toJson(),
                        ],
                        [
                            'id' => $category[1]->id,
                            'name' => $category[1]->name,
                            // 'created_at' => $category[1]->created_at->toJson(),
                            // 'updated_at' => $category[1]->updated_at->toJson(),
                        ],
                    ],
                    'total' => 2
                ]
                );
    }



}
