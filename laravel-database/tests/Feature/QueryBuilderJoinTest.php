<?php

namespace Tests\Feature;

use App\Constant\TableDbConstant;
use Illuminate\Database\Query\Builder;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use function PHPUnit\Framework\assertNotNull;

class QueryBuilderJoinTest extends TestCase
{
    protected Builder $categories;
    protected Builder $products;

    protected function setUp(): void
    {
        parent::setUp();
        $this->categories = DB::table(TableDbConstant::CATEGORIES_TABLE);
        $this->products = DB::table(TableDbConstant::PRODUCTS_TABLE);

        $this->cleanUpDb();
        $this->generateDataCategories();
        $this->generateDataProducts();
    }

    // //* Testing 
    public function testJoin(): void
    {
        $collection = $this->products->join("categories","products.category_id","=","categories.id")
        ->select("products.name", "categories.name as category_name")->get();
        
        self::assertCount(4,$collection);
        
        $collection->each(function($item){
            Log::info(json_encode($item));
        });
    }
    
    public function testOrder(){
        $collection = $this->products->join("categories","products.category_id","=","categories.id")
        ->select("products.name", "products.price", "categories.name as category_name")->orderBy('products.price','asc')->get();
        
        self::assertCount(4,$collection);
        
        $collection->each(function($item){
            Log::info(json_encode($item));
        });
        
    }

    public function testPaging(){

        //* skenario page 2 data per page 2

        $dataPerPage = 3;
        $page = 2;
        $skip =  $dataPerPage * ($page-1);

        $collection = $this->products
        ->join("categories","products.category_id","=","categories.id")
        ->select("products.name", "products.price", "categories.name as category_name")
        ->orderBy('products.price','asc')
        ->take($dataPerPage) //* data-per-paging
        ->skip($skip) //* data-per-paging * page-1 ?
        ->get();

        self::assertCount(1,$collection);
        self::assertEquals('ASUS A412 DA',$collection[0]->name);
        
    }

    public function testChunk() : void {
        //* chunk
        $data = $this->products
            ->orderBy("price","asc")
            ->chunk(2, function($products){
                Log::info("Start Chunk");
                self::assertCount(2,$products);
                foreach($products as $p)
                {
                    Log::info("P itu : " . json_encode($p));
                }
                Log::info("End Chunk");
            });
    }

    public function testLazy() : void {
        $page = 2; // Halaman yang ingin diambil
        $perPage = 3; // Jumlah data per halaman
        
        $data = $this->products
            ->orderBy("price","asc")
            ->lazy(4) // Kueri dieksekusi secara "lazy"
            ->skip(3)
            ->take(1);
            self:assertNotNull($data);
        $data->each(function ($dt){
            Log::info(json_encode($dt));
        });
    }

    //* how to compare perfoma ?
    public function testCursor() : void{
        $this->generateDummy();
        $data = $this->categories
        ->cursor()->skip(10)->take(5);
        self:assertNotNull($data);

        $data->each(function ($dt){
            Log::info(json_encode($dt));
        });
    }

    public function testQueryBuilderRawAggregate()
    {
        $collection = $this->products->select(
            DB::raw('count(*) as total_product'),
            DB::raw('min(price) as min_price'),
            DB::raw('max(price) as max_price')
        )->get();

        Log::info(json_encode($collection));
        self::assertTrue(true);
    }

    //* GROUP BY
    public function testGroupBy()
    {
        $collection = $this->products
        ->select("category_id", DB::raw("count(*) as total_product"))
        ->groupBy("category_id")
        ->get();
        Log::info(json_encode($collection));
        self::assertTrue(true);
    }

    
    public function testHaving()
    {
        $collection = $this->products
        ->select("category_id", DB::raw("count(*) as total_product"))
        ->groupBy("category_id")
        ->having("total_product",">",2)
        ->get();
        Log::info(json_encode($collection));
        self::assertCount(1,$collection);
    }

    public function testLocking()
    {
        DB::transaction(function(){
            $collection = $this->products
            ->where("id","=","INFINIXHOT10")
            ->lockForUpdate()
            ->get();

            self::assertCount(1,$collection);
        });
    }

    public function testPaginate()
    {
        $this->generateDummy();
        $page = 1;
        $paginate = $this->categories->paginate(perPage: 15, page: $page);
        self::assertEquals(1,$paginate->currentPage());
        self::assertEquals(15,$paginate->perPage());
        self::assertEquals(7,$paginate->lastPage()); //* total page ?
        self::assertEquals(104,$paginate->total());
        }


// * HELPING TESTING SCENARIOS * ==============================================================/ 
  
    private function cleanUpDb(): void
    {
        $this->categories->delete();
        $this->products->delete();
    }


    private function generateDataCategories(): void
    {
        $data = [
            [
                "id" => "SMARTPHONE",
                "name" => "Smartphone",
                "description" => "Smartphone category",
                "created_at" => "2020-05-05 00:00:00"
            ],
            [
                "id" => "FOOD",
                "name" => "Food",
                "description" => "Food category",
                "created_at" => "2021-05-05 00:00:00"
            ],
            [
                "id" => "LAPTOP",
                "name" => "Laptop",
                "description" => "Laptop category",
                "created_at" => "2022-05-05 00:00:00"
            ],
            [
                "id" => "Accesorries HP",
                "name" => "Accessories HP",
                "description" => "Accessories HP category",
                "created_at" => "2023-05-05 00:00:00"
            ]
        ];

        $this->categories->insert($data);
    }

    private function generateDataProducts(): void
    {
        $data = [
            [
                "id" => "ASUS-A412DA",
                "name" => "ASUS A412 DA",
                "description" => "ASUS VIVOBOOK A412 DA AMD RYZEN 3500U",
                "price" => 7_900_000,
                "category_id" => "LAPTOP"
            ],
            [
                "id" => "INFINIXHOT10",
                "name" => "INFINIX HOT 10",
                "description" => "INFINIX HOT 10 VARIANT 256 GB/BLUE",
                "price" => 2_100_000,
                "category_id" => "SMARTPHONE"
            ],
            [
                "id" => "VIVO 5Y",
                "name" => "VIVO 5Y",
                "description" => "VIVO 5Y",
                "price" => 5_900_000,
                "category_id" => "SMARTPHONE"
            ],
            [
                "id" => "OPPO",
                "name" => "OPPO",
                "description" => "OPPO",
                "price" => 2_900_000,
                "category_id" => "SMARTPHONE"
            ],
        ];

        $this->products->insert($data);
    }

    private function generateDummy(){
        $data = [];
        for($i = 0 ; $i < 100 ; $i ++){
            $data[] = [
                "id" => "CATEGORY $i",
                "name" => "Category $i",
                "description" => "category $i dummy",
                "created_at" => "2022-05-05 00:00:00"
            ];
        }
        $this->categories->insert($data);
    }
}
