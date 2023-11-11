<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Voucher;
class VoucherSeeder extends Seeder
{
    public function run(): void
    {
        for($i = 0 ; $i < 5 ; $i++){
            $index = $i+1;
            $voucher = Voucher::create([
                "name" => "Sample Voucher $index",  
            ]);
        }
    }
}
