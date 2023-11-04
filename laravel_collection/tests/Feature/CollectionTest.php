<?php

namespace Tests\Feature;

use App\Data\Person;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertEqualsCanonicalizing;
use function PHPUnit\Framework\assertTrue;

class CollectionTest extends TestCase
{
    public function testCreateCollection(): void
    {
        $collection = collect([1,2,3]);
        //* Check data & index
        $this->assertEquals([1,2,3],$collection->all());
        //* Check data 
        $this->assertEqualsCanonicalizing([3,2,1],$collection->all());
    }

    public function testForEach()
    {
        $collection = collect([1,2,3,4,5,6]);

        foreach($collection as $key => $value)
        {
            self::assertEquals($key+1,$value);
        }
    }

    public function testCollectionOperation()
    {
        //* push
        $collection = collect([1,2,3,4,5]);
        $collection->push(6,7);
        $this->assertEquals([1,2,3,4,5,6,7],$collection->all());
        
        //*pop
        $result = $collection->pop(2);
        self::assertEquals([7,6], $result->all());
        $this->assertEquals([1,2,3,4,5],$collection->all());
        
        //*prepend
        $collection = $collection->prepend(0);
        $this->assertEquals([0,1,2,3,4,5],$collection->all());

    }


    public function testMappingNormal()
    {
        $collection = collect([1,2,3]);

        $result = $collection->map(function($item){
            return $item * 2;
        });

        $this->assertEqualsCanonicalizing([2,4,6],$result->all());
    }


    public function testMapInto()
    {
        $collection = collect(["Alfons"]);
        $result = $collection->mapInto(Person::class);

        $this->assertEquals([new Person("Alfons")],$result->all());
    }


    public function testMapSpread()
    {
        $collection = collect([["alfons", "setiawan"],[ "bambang","surucup"]]);

        $result = $collection->mapSpread(function($first, $last){
            return  new Person($first . " " . $last);
        });

        $this->assertEquals([
            new Person("alfons setiawan"),
            new Person("bambang surucup") 
        ],
        $result->all()
    );
    }

    public function testMapToGroup()
    {
        $collection = collect([
            [
                "name" => "ucup",
                "departement" => "IT"
            ],
            [
                "name" => "udin",
                "departement" => "IT"
            ],
            [
                "name" => "mamat",
                "departement" => "HR"
            ]
        ]);

        $result = $collection->mapToGroups(function($item){
            return[$item["departement"] => $item["name"]];
        });


        assertEquals([
            "IT" => collect(["ucup", "udin"]),
            "HR" => collect(["mamat"]),
        ], $result->all()
    );
    }


    //* Zipping
    public function testZip()
    {
        $name = collect(["alfons","ucup","udin"]);
        $umur = collect([21,30,32]);
        $zip = $name->zip($umur);

        assertEquals(
            [
                collect(["alfons",21]),
                collect(["ucup",30]),
                collect(["udin",32])
            ]
        ,$zip->all()
        );
    }

    //* concat
    public function testConcat(){
        $collection1 = collect([1,2,3]);
        // $collection2 = collect([4,5,6]);
        $collection2 = $collection1->map(function($item){
            return $item * 2;
        });
        $concat = $collection1->concat($collection2);

        assertEquals([1,2,3,2,4,6],$concat->all());
    }

    //* combine
    public function testCombine(){
        $collection1 = ["name","country"];
        $collection2 = ["alfons","Indonesia"];
        $collection3 = collect($collection1)->combine($collection2);

        assertEquals(
            [
                "name" => "alfons",
                "country" => "Indonesia"
            ],
            $collection3->all()
        );
    }


    //* zipping operation
    public function testCollapse()
    {
        $collection = collect([
            [1,2,3],
            [4,5,6],
            [7,8,9]
        ]);

        
        $result = $collection->collapse();
        assertEquals([1,2,3,4,5,6,7,8,9],$result->all());
    }

    public function testflatMap()
    {
        $collection = collect([
            [
                "name" => "bambang",
                "hobbies" => ["coding", "gaming"]
            ],
            
            [
                "name" => "tono",
                "hobbies" => ["run", "sleep"]
            ],

            [
                "name" => "budi",
                "hobbies" => ["coding", "sleep"]
            ],
        ]);

        $hobbies = $collection->flatMap(function($item){
            return $item["hobbies"];
        })->unique();

        // Log::debug($hobbies);
        assertEquals(["coding","gaming","run","sleep"],$hobbies->all());

    }

    //* String Representation 
    //* glue -> separator, final glue
    public function testJoin()
    {
        $collection = collect(['alfons','setiawan','jacub']);
        assertEquals("alfons setiawan jacub", $collection->join(" "));
        assertEquals("alfons setiawan_jacub", $collection->join(" ","_"));
    }
    //* filtering 

    public function testFilter()
    {
        $collection = collect([
            "Eko" => 100,
            "Budi" => 90,
            "Bambang" => 80
        ]);

        //* function params (value ,key)
        $result =$collection->filter(function($item){
            return $item >= 90;
        });

        assertEquals(
            ["Eko" => 100,
            "Budi" => 90], $result->all()
        );
    }

    //* hati-hati ketika menggunakan array karena indexnya -value nya beneran dihapus
    public function testFilterMap(){
        $collection = collect([1,2,3,4,5,6,7,8,9]);

        $result = $collection->filter(function($value,$key){
            return $value % 2 == 0;
        });
        
        //* will error
        // assertEquals([2,4,6,8],$result->all());
        //* will not
        assertEqualsCanonicalizing([2,4,6,8],$result->all());

    }


    //* public function
    public function testPartition()
    {
        $collection = collect([
            "Bambang" => 100,
            "Ucup" => 100,
            "Fares" => 40,
            "Tono" => 60,
            "Alfons" => 90,
            "Udin" => 50,
            "Lovy" => 100,
        ]);

        [$passed, $notPassed] = $collection->partition(function($value,$key){
            return $value>60;
        });

        // Log::debug($passed);
        // Log::debug($notPassed);

        assertEquals(collect(["Bambang" => 100,"Ucup" => 100,"Alfons" => 90,"Lovy" => 100]), $passed);
        //* change to not passed data
        // assertEquals(collect(["Bambang" => 100,"Ucup" => 100,"Alfons" => 90,"Lovy" => 100]), $passed);
     }


     public function testHas(){
        $collection = collect([2,3,4,5,1,10]);

        Log::debug($collection->has(4));
        //* Fail, check key
        // assertEquals(true,$collection->has(10));
        assertEquals(true,$collection->has(2));
        assertEquals(true, $collection->hasAny(10,2));

        $collection2 = collect(["bambang","ucup","udin"]);

        assertEquals(true,$collection2->contains("bambang"));
        //assertEquals(true,$collection2->contains(0,"bambang"));
        assertEquals(true,$collection2->contains("bambang",0));
        assertEquals(true,$collection2->contains(function($data){
            // pengembalian strpos antara index tempat ditemukan dan false jika tidak ditemukan
            $check = strpos($data, "in");
            // Log::debug("$data : $check");
            return $check !== false;
        }));
    }

    //* groupBy -> by key & by a function
    public function testGroup(){        
        function groupByKey(){
            $collection = collect([
                [
                    "name" => "alfons",
                    "last_education" => "sd"
                ],
                [
                    "name" => "ucup",
                    "last_education" => "smp"
                ],
                [
                    "name" => "bambang",
                    "last_education" => "sma"
                ],
                [
                    "name" => "yono",
                    "last_education" => "s1"
                ],
                [
                    "name" => "budiman",
                    "last_education" => "s2"
                ],
                [
                    "name" => "gibran",
                    "last_education" => "s1"
                ],
                [
                    "name" => "raka",
                    "last_education" => "sma"
                ],
            ]);

            Log::debug($collection->groupBy("last_education"));
        }

        function groupByFunction(){

        }

        groupByKey();
        groupByFunction();
        assertTrue(true);
    }
}
