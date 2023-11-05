<?php

namespace Tests\Feature;

use App\Models\Category;
use Database\Seeders\CategorySeeder;
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

        $result = Category::query()->insert($categories);

        self::assertTrue($result);
        //* mirip dengan QueryBuilder, bedanya table sudah direpresentasi class Model.
        $total = Category::query()->count();
        self::assertEquals(10,$total);
    }


    public function testFind()
    {
        $this->seed(CategorySeeder::class);
        $category = Category::query()->find("FOOD");
        self::assertNotNull($category);
        self::assertEquals("FOOD", $category->id);
        self::assertEquals("Food", $category->name);
        self::assertEquals("Food Category", $category->description);
    }

    public function testUpdate()
    {
        $this->seed(CategorySeeder::class);
        $category = Category::query()->find('FOOD');
        $category->name ="FOOD UPDATE";
        $result = $category->update(); //* didalem methodnya ada save
        $categoryAfterUpdate = Category::query()->find('FOOD');
        
        self::assertEquals($category->name, $categoryAfterUpdate->name);
        self::assertTrue($result);
    }

    public function testSelect()
    {
        for($i = 0;$i<5;$i++){
            $category = new Category();
            $category->id = "ID $i";
            $category->name = "NAME $i";
            $category->save();
        }


        $categories = Category::whereNull("description")->get();

        self::assertCount(5,$categories);

        $categories->each(function($category){
            $category->description = "categoryupdate";
            $category->update();
        });
    }

    public function testSelectAndUpdate()
    {
        $categories = [];
        for($i = 0 ; $i < 10; $i++)
        {
            $categories[] = [
                'id' => "ID $i",
                'name' => "Name $i",
            ];
        }
        $result = Category::insert($categories);


        $categories = Category::whereNull("description")->update([
            "description" => "updated"
        ]);

        $categories = Category::where("description", "=", "updated")->get();
        self::assertcount(10, $categories);
    }

    public function testDelete()
    {
        $this->seed(CategorySeeder::class);

        $category = Category::find("FOOD");
        $result = $category->delete();
        self::assertTrue($result);

        $total = Category::query()->count();
        self::assertEquals(0, $total);
    }


    public function testDeleteMany()
    {
        $categories = [];
        for($i = 0 ; $i < 10; $i++)
        {
            $categories[] = [
                'id' => "ID $i",
                'name' => "Name $i",
            ];
        }
        $result = Category::insert($categories);
        self::assertTrue($result);

        $total = Category::count();
        self::assertEquals(10, $total);

        Category::whereNull("description")->delete();
        
        $total = Category::count();
        self::assertEquals(0, $total);
    }
}
