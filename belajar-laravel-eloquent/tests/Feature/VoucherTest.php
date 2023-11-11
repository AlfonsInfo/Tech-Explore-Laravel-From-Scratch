<?php

namespace Tests\Feature;

use App\Models\Voucher;
use Database\Seeders\VoucherSeeder;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use function PHPUnit\Framework\assertEquals;

class VoucherTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
        DB::delete("delete from vouchers");
    }

    public function testCreateVoucher(): void
    {
        $voucher = new Voucher([
          "name" => "Sample Voucher",  
        ]);
        $result = $voucher->save();
        $count = Voucher::count();
        self::assertTrue($result);
        self::assertEquals(1,$count);
    }

    public function testSoftDelete() : void {
        $this->seed(VoucherSeeder::class);
        
        $voucher = Voucher::query()->where("name","=", "Sample Voucher 1")->first();
        $voucher->delete();
        
        //* impact soft delete when searching again its will be null after delete
        $voucher = Voucher::query()->where("name","=", "Sample Voucher 1")->first();
        self::assertNull($voucher);
    }
    
    public function testWithTrashed() : void{
        $this->seed(VoucherSeeder::class);
        $voucher = Voucher::query()->where("name","=", "Sample Voucher 1")->first();
        $voucher->delete();
        
        //* impact soft delete when searching again its will be null after delete
        $voucher = Voucher::query()->withTrashed()->where("name","=", "Sample Voucher 1")->first();
        self::assertNotNull($voucher);
    }

    public function testLocalScope() : void{
        Voucher::create([
            "name" => "Sample Voucher 1",  
            "is_active" => true
        ]); 
        Voucher::create([
            "name" => "Sample Voucher 2",  
            "is_active" => false
        ]);

        self::assertNotNull(Voucher::query()->active()->get());
        self::assertNotNull(Voucher::query()->nonActive()->get());
    }
}
