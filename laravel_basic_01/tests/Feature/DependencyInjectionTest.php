<?php

namespace Tests\Feature;

use App\Data\Bar;
use App\Data\Foo;
use App\Data\Person;
use Illuminate\Contracts\Container\BindingResolutionException;
use Tests\TestCase;

use function PHPUnit\Framework\assertEquals;

class DependencyInjectionTest extends TestCase
{


    //* function name include test
    public function testManualDependencyInjection(): void
    {
        $Foo = new Foo();
        $Bar = new Bar($Foo);

        self::assertEquals("Foobar",$Bar->Bar()); 


    }

    //* dependency injection with Service Container & Application Class

    public function testCreateDependency() : void{
        $foo =  $this->app->make(Foo::class);
        $foo2 =  $this->app->make(Foo::class);

        self::assertEquals("Foo", $foo->foo());
        self::assertEquals("Foo", $foo2->foo());
        self::assertNotSame($foo,$foo2);
    }

    public function testBindError() : void{
        $this->assertThrows(function(){
            $person = $this->app->make(Person::class);
        },BindingResolutionException::class);
    }
    
    public function testBindSuccess() : void{
        $this->app->bind(Person::class, function($app){
            return new Person("alfons", "setiawan");
        });
        
        $person1 = $this->app->make(Person::class);
        $person2 = $this->app->make(Person::class);
    
 
        self::assertEquals("alfons",$person1->firstName);
        self::assertNotSame($person1,$person2);
    }

    //* use same object
    public function testSingleton() : void{
        $this->app->singleton(Person::class, function($app){
            return new Person("alfons", "setiawan");
        });
        
        $person1 = $this->app->make(Person::class);
        $person2 = $this->app->make(Person::class);
    
 
        self::assertEquals("alfons",$person1->firstName);
        self::assertSame($person1,$person2);
    }

    //* instance -> menggunakan objek yang sudah ada

    public function testInstance() : void{

        $person = new Person("mamang", "jamet");

        $this->app->instance(Person::class,$person);
        
        $person1 = $this->app->make(Person::class);
        $person2 = $this->app->make(Person::class);
    
 
        self::assertSame($person,$person1);
        self::assertSame($person1,$person2);
    }

    public function testDependencyInjection() : void{

        $this->app->singleton(Foo::class, function($app){
            return new Foo();
        });
        
        $foo = $this->app->make(Foo::class);
        $bar = $this->app->make(Bar::class);
        $bar1 = $this->app->make(Bar::class);
        $bar2 = $this->app->make(Bar::class);

        var_dump($foo,$bar->foo);
        self::assertSame($foo, $bar->foo);
        self::assertSame($bar1->foo,$bar2->foo);
        self::assertNotSame($bar1,$bar2);
    }


    public function testDependencyInjectionInClosure()
    {
        $this->app->singleton(Foo::class, function($app){
            return new Foo();
        });

        $this->app->singleton(Bar::class, function($app){
            return new Bar($app->make(Foo::class));
        });
        
        $bar1 = $this->app->make(Bar::class);
        $bar2 = $this->app->make(Bar::class);

        assertEquals($bar1, $bar2);
    }

}
