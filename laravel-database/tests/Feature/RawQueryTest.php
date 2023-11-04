<?php


use Illuminate\Support\Facades\DB;
use Tests\TestCase;

use function PHPUnit\Framework\assertEquals;

class RawQueryTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        DB::delete("DELETE FROM categories");
    }
    public function testCreate(): void
    {
        DB::insert("INSERT INTO categories(id, name, description, created_at) VALUES (?,?,?,?)",[
            "GADGET", "Gadget", "Gadget Category", "2023-05-05 00:00:00"
        ]);

        $result = DB::select("SELECT * FROM categories WHERE id = ?", ["GADGET"]);

        assertEquals(1, count($result));
        assertEquals('GADGET', $result[0]->id);
        assertEquals('Gadget', $result[0]->name);
    }

    public function testUpdate(): void{
        //* insert
        DB::insert("INSERT INTO categories(id, name, description, created_at) VALUES (?,?,?,?)",[
            "GADGET", "Gadget", "Gadget Category", "2023-05-05 00:00:00"
        ]);

        //* if not naming binding,  the order influence
        //* instead, using named binding
        DB::update("UPDATE categories SET name = :name , description = :description WHERE id = :id",[
            'id'=> 'GADGET',
            'name' => 'GADGET CATEGORY',  
            'description'=> 'GADGET CATEGORY IS CATEGORY CONTAINS MOBILE DEVICE'
        ]);

        $result = DB::select("SELECT * FROM categories WHERE id = ?", ["GADGET"]);


        

        assertEquals("GADGET CATEGORY IS CATEGORY CONTAINS MOBILE DEVICE", $result[0]->description );
    }




}
