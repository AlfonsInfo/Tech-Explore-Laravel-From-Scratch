<?php

namespace Tests\Feature;

use App\Models\Voucher;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;

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
        //   "voucher_code" => "12313231212",  
        ]);
        $result = $voucher->save();
        $count = Voucher::count();
        self::assertTrue($result);
        self::assertEquals(1,$count);
    }
}
