<?php

namespace Tests\Feature;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Illuminate\Support\Facades\Log;

use function PHPUnit\Framework\assertCount;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertTrue;

class QueryBuilderTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        DB::table('categories')->delete();
        DB::table('counters')->delete();
        DB::table("counters")->insert(["id" => "sample", "counter" => 0]);
    }


    public function testCreate(): void
    {
        //* Return value query builder
        DB::table("categories")->insert([
            'id' => 'GADGET',
            'name' => 'Gadget',
            'description' => 'Gadget Categories',
        ]);
        DB::table("categories")->insert([
            'id' => 'FOOD',
            'name' => 'Food',
            'description' => 'Food Categories',
        ]);
        $result = DB::table("categories")->count();

        self::assertEquals(2, $result);
    }


    //* Query Builder select
    //* Function select() get() irst(0 pluck(), hasil dari query builder select adalah laravel collection

    public function testRead() : void{
        $this->testCreate();

        $collection = DB::table("categories")->select(["id","name"])->get();
        Log::info($collection);
        self::assertNotNull($collection);

        $collection->each(function($record){
            Log::info(json_encode($record));
        });
    }

    public function insertMultipleCategories() : void{
        DB::table("categories")
        ->insert(["id" => "SMARTPHONE", "name" => "Smartphone" , "description" => "Smartphone category" , "created_at" => "2020-05-05 00:00:00"]);
        DB::table("categories")
        ->insert(["id" => "FOOD", "name" => "Food" , "description" =>  "food category" , "created_at" => "2021-05-05 00:00:00"]);
        DB::table("categories")
        ->insert(["id" => "LAPTOP", "name" => "Laptop" , "description" =>  "Laptop category" , "created_at" => "2022-05-05 00:00:00"]);
        DB::table("categories")
        ->insert(["id" => "Accesorries HP", "name" => "accessories hp" , "description" =>  "Accessories HP category" , "created_at" => "2023-05-05 00:00:00"]);
    }

    public function testWhere() : void{
        $this->insertMultipleCategories();

        $categories =  DB::table("categories");

        $result = $categories->where(function(Builder $builder){
            $builder->whereRaw("LOWER(id) LIKE LOWER(?)",['%smart%']);
            $builder->orWhere('id', '=', 'LAPTOP' );
        })->get();

        Log::info($result);
        $this->assertCount(2, $result);
        
        $result->each(function ($item){
            Log::info(json_encode($item));
        });
    }


    public function testWhereBetween() : void{
        $this->insertMultipleCategories();
        
        $categories =  DB::table("categories");
        
        $result = $categories->whereBetween("created_at",["2021-01-01","2022-12-12"])->get();
        $this->assertCount(2, $result);
    }

    public function testWhereIn() : void{
        $this->insertMultipleCategories();
        
        $categories =  DB::table("categories");
        
        $result = $categories->whereIn("id",["SMARTPHONE","FOOD"])->get();
        $this->assertCount(2, $result);
    }
    
    //* others : where null , where date

    //* UPDATE
    
    public function testUpdate(){
        $this->insertMultipleCategories();        
        $categories =  DB::table("categories");
        $categories->where("id", '=', "SMARTPHONE")->update([
            'name' => "Handphone"
        ]);
        
        $collection = $categories->where("name","=","Handphone")->get();
        self::assertCount(1, $collection);
    }

  

    //* Upsert -> Update or insert ( if data doesn't exist insert data baru)

    public function testQueryBuilderUpdateOrInsert()
    {
        DB::table("categories")->updateOrInsert(
            [
                'id' => "VOUCHER"
            ],
            [
                "name" =>  "Voucher",
                "description" => "Ticket and Voucher",
                "created_at" => "2020-10-10 10:10:10"
            ]
        );

        DB::table("categories")->updateOrInsert([
            'id' => "VOUCHER"
        ],
        [
            "name" =>  "Voucher Di update",
            "description" => "Ticket and Voucher",
            "created_at" => "2020-10-10 10:10:10"
        ]
    );

    $result = DB::table("categories")->where("name","=","Voucher Di update")->get();
    self::assertCount(1, $result);
    }

    public function testIncrement()
    {
        DB::table("counters")->where("id","=","sample")->increment('counter',1);
        $result = DB::table("counters")->where("id","=","sample")->get();
        assertEquals(1,$result[0]->counter);
    }


    public function testDelete(){
        $this->insertMultipleCategories();
        $categories =  DB::table("categories");
        $categories->delete("SMARTPHONE");
        $result = $categories->where("id", '=', "SMARTPHONE")->get();

        assertCount(0, $result);
    }
}
