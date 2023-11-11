<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Wallet;
use Database\Seeders\CategorySeeder;
use Database\Seeders\CustomerSeeder;
use Database\Seeders\ProductsSeeder;
use Database\Seeders\WalletSeeder;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
class RelationTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
        DB::delete("delete from wallets");
        DB::delete("delete from customers");
        DB::delete("delete from products");
        DB::delete("delete from categories");

    }

    public function testOneToOne() : void{
        $this->seed(CustomerSeeder::class);
        $this->seed(WalletSeeder::class);

        $customer = Customer::query()->with(["wallet"])->find("ALFONSUS");
        self::assertNotNull($customer);
        self::assertNotNull($customer->wallet);
    }



    public function testOneToMany() : void{
        $this->seed(CategorySeeder::class);
        $this->seed(ProductsSeeder::class);

        $category = Category::query()->with(["Products"])->find("FOOD");
        Log::info($category);
        self::assertNotNull($category);
        self::assertNotNull($category->products[0]);

    }

    public function testOneToOneQuery() : void{
        $customer = new Customer();
        $customer->id = "ALFONS";        
        $customer->name = "alfons";        
        $customer->email = "alfons@example.com";        
        $customer->save();

        $wallet = new Wallet();
        $wallet->amount = 1000000;

        $customer->wallet()->save($wallet);
        
        self::assertNotNull($wallet->customer_id);
    }

}
