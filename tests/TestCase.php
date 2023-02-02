<?php

namespace Tests;

use Exception;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, DatabaseMigrations;

    private Generator $faker;
    public $user;
    public function setUp()
    : void {

        parent::setUp();
        $this->faker = Factory::create();
        Artisan::call('migrate:refresh');
        Artisan::call('passport:install');
        $this->user = User::factory()->create([
            "first_name" => "first_name",
            "last_name" => "last_name",
            "email" => "testmail@example.com",
            "password" => Hash::make("12345678")
        ]);

    }
    
    public function __get($key) {
    
        if ($key === 'faker')
            return $this->faker;
        throw new Exception('Unknown Key Requested');
    }
}
