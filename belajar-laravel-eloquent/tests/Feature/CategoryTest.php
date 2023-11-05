<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
class CategoryTest extends TestCase
{

    protected function setUp() : void{
        parent::setUp();
        DB::delete("delete from categories");
    }


    public function testInsert(): void
    {
        $category = new Category();
        $category->id = "GADGET";
        $category->name = "gadget";
        $result = $category->save();

        self::assertTrue($result);

        //*kebanyakan tutorial & ini bisa terjadi karena __call, jika method tidak ditemukan maka method ini yang akan dipanggil
        //* __call __callstatic -> magic method
        //Category::where() 

        //* sebenarnya
        // Category::query()->where()
    }
    
    public function testInsertManyCategories()
    {
        $categories = [];
        for($i = 0 ; $i < 10; $i++)
        {
            $categories[] = [
                'id' => "ID $i",
                'name' => "Name $i",
            ];
        }
    }
}
