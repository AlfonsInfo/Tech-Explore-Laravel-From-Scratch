<?php

namespace Tests\Feature;

use Exception;
use Illuminate\Database\QueryException;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Throwable;

use function PHPUnit\Framework\assertEquals;



class DbTransactionTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        DB::delete("DELETE FROM categories");
    }

    public function testSuccessTransaction()
    {
    
        DB::transaction(function(){
            DB::insert("INSERT INTO categories(id, name, description, created_at) VALUES (?,?,?,?)",[
                "GADGET", "Gadget", "Gadget Category", "2023-05-05 00:00:00"
            ]);

            DB::insert("INSERT INTO categories(id, name, description, created_at) VALUES (?,?,?,?)",[
                "FOOD", "Food", "Food Category", "2023-05-05 00:00:00"
            ]);
        });

        $result = DB::select("SELECT * FROM categories");
        assertEquals(2,count($result));
    }

    public function testFailedTransaction()
    {
        $this->expectException(\Exception::class);
        DB::transaction(function(){
            DB::insert("INSERT INTO categories(id, name, description, created_at) VALUES (?,?,?,?)",[
                "GADGET", "Gadget", "Gadget Category", "2023-05-05 00:00:00"
            ]);

            DB::insert("INSERT INTO categories(id, name, description, created_at) VALUES (?,?,?,?)",[
                "FOOD", "Food", "Food Category", "2023-05-05 00:00:00"
            ]);

            throw new \Exception("Transaksi Gagal");
        });

        $result = DB::select("SELECT * FROM categories");
        assertEquals(0,count($result));
    }
    
    
    public function testManualTransactionSuccess()
    {
        try{
            DB::beginTransaction();
            DB::insert("INSERT INTO categories(id, name, description, created_at) VALUES (?,?,?,?)",[
                "GADGET", "Gadget", "Gadget Category", "2023-05-05 00:00:00"
            ]);

            DB::insert("INSERT INTO categories(id, name, description, created_at) VALUES (?,?,?,?)",[
                "FOOD", "Food", "Food Category", "2023-05-05 00:00:00"
            ]);
            DB::commit();
        }catch(QueryException $error){
            DB::rollBack();
            throw $error;
        }
        
        $result = DB::select("SELECT * FROM categories");
        assertEquals(2,count($result));
    }

    public function testManualTransactionFailed()
    {
        
        $this->expectException(\Exception::class);
        try{
            DB::beginTransaction();
            DB::insert("INSERT INTO categories(id, name, description, created_at) VALUES (?,?,?,?)",[
                "GADGET", "Gadget", "Gadget Category", "2023-05-05 00:00:00"
            ]);

            DB::insert("INSERT INTO categories(id, name, description, created_at) VALUES (?,?,?,?)",[
                "FOOD", "Food", "Food Category", "2023-05-05 00:00:00"
            ]);
            throw new \Exception("error dah");
            DB::commit();
        }catch(\Exception $error){
            DB::rollBack();
            throw $error;
        }
        
        $result = DB::select("SELECT * FROM categories");
        assertEquals(2,count($result));
    }
}
