<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Psy\ConfigPaths;
use Psy\VersionUpdater\SelfUpdate;

use function PHPUnit\Framework\assertEquals;

class FacadeTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testConfig(): void
    {
        $configPath = 'customconfig.author.first';
        $firstName1 = config($configPath);
        $firstName2 = Config::get($configPath);
        
        self::assertEquals($firstName1, $firstName2);
    }
    
    public function testConfigDependency() : void{
        $configPath = 'customconfig.author.first';
        
        $config = $this->app->make("config");
        $firstName1 = $config->get($configPath);
        $firstName2 = Config::get($configPath);
        self::assertEquals($firstName1, $firstName2);
    }


    public function testConfigMock()
    {
        //* kekurangan menggunakan static function biasanya sulit untuk di test, karena mocking static function sangat sulit
        //* laravel menyediakan fitur untuk mocking facades
        Config::shouldReceive('get')
            ->with('customconfig.author.first')
            ->andReturn("Bambang");

        $firstName = Config::get("customconfig.author.first");

        //* confignya dari mocking bukan dari real facades
        self::assertEquals("Bambang",$firstName);
    }

}
