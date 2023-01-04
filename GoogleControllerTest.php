<?php

namespace Tests\Unit\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Passport;


class GoogleControllerTest extends TestCase
{

    public function test_getGoogleLoginUrl()
    {

        if (env('GOOGLE_OAUTH_TEST') && empty(env('GOOGLE_OAUTH_TEST_CODE'))){
           
            $response = $this->get('api/google/login/url');

            $google_login_url = $response->getOriginalContent();

            if (!empty($google_login_url)) {
       
               print  "\xA".'test_getGoogleLoginUrl() URL: '.$google_login_url;
           
            } else {

                $response->assertStatus(500);

            }
        } else {

            print "\xA".'test_getGoogleLoginUrl skip';

            $this->assertTrue(true);
        }
    }


    public function test_login_google()
    {

        if(env('GOOGLE_OAUTH_TEST') && !empty(env('GOOGLE_OAUTH_TEST_CODE'))){
      
        $auth_code = env('GOOGLE_OAUTH_TEST_CODE');
       
        $payload = [
            "auth_code" => $auth_code,
            'email' => '',
            'password' => '',
            "remember" => 1
        ]; 

        $response = $this->json('post', 'api/login', $payload);
        
        $response_content = $response->getOriginalContent();

        if (!empty($response_content['error'])) {
  
            $this->fail('test_login_google() error: '.$response_content['error']);

        } else {

            print "\xA".'test_login_google() ACCESS_TOKEN: '.$response_content;
              
            $response->assertStatus(200);

        }          
          
       } else {

            print "\xA".'test_login_google skip';

            $this->assertTrue(true);

       }

    }

    public function test_register_and_login()
    {
        if (!env('GOOGLE_OAUTH_TEST')) {

        User::factory()->create([
            "first_name" => "first_name",
            "last_name" => "last_name",
            "email" => "testmail@example.com",
            "password" => Hash::make("12345678")
        ]);    

        $user = User::where('email', 'testmail@example.com')->get()->first();
        
        if(!empty($user)){     
        
        $payload = [
            "auth_code" => '',
            "email" => "testmail@example.com",
            "password" => "12345678",
            "remember" => 1,
            "noverify_email" => true
        ];

            $response = $this->json('post', 'api/login', $payload);

            $response_content = $response->getOriginalContent();

                if (!empty($response_content) && empty($response_content["errors"])) {
                    
                    $response->assertStatus(200);
               
                } else {

                    $message = 'test_register_and_login fail';

                    if (!empty($response_content["errors"])) {
                 
                        $message =  'test_register_and_login error: ' . $response_content["errors"];
                    }

                    $this->fail($message);
               
                }

        }else{

            $this->fail('test_register_and_login registered user not found');

        }

        } else {

            print "\xA".'test_register_and_login skip';

            $this->assertTrue(true);

        }

    }

    public function test_getWeather()
    {
         
            $payload = [
               "geo_latitude" => "50.4676612",
               "geo_longitude" => "30.4051859",
               "geo_city" => "Kyiv"
            ];

            Passport::actingAs(
                User::factory()->create([
                    "first_name" => "first_name",
                    "last_name" => "last_name"
                ]),
                ['api/home']
            );


            $response = $this->json('GET', 'api/home', $payload);

            $response_content = $response->getOriginalContent();

            if(!empty($response_content['message'])){

                print "\xA".'test_getWeather message: '.$response_content['message'];

            } else {
  
            $response->assertStatus(200)->assertJsonStructure([
                "user" => [
                  "first_name",
                  "last_name",
                  "email",
                  "profile",
                  "status",
                  "created_at",
                  "updated_at"
                ],
                "main" => [
                  "temp",
                  "pressure",
                  "humidity",
                  "temp_min",
                  "temp_max",
                  "city"  
                ]
            ]);

        }
    }

  
}
