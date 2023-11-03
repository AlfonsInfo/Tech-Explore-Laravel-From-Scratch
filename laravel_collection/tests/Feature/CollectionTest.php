<?php

namespace Tests\Feature;

use App\Data\Person;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use function PHPUnit\Framework\assertEquals;

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
}
