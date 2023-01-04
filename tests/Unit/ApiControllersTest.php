<?php

namespace Tests\Unit\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Passport;

class ApiControllersTest extends TestCase
{

    public function test_getGoogleLoginUrl()
    {

        if (env('GOOGLE_OAUTH_TEST') && empty(env('GOOGLE_OAUTH_TEST_CODE'))){
           
            $response = $this->get('api/google/login/url');

            $google_login_url_data = $response->getOriginalContent();

            $google_login_url = $google_login_url_data['data'];

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

        $response = $this->json('post', 'api/auth', $payload);
        
        $response_content = $response->getOriginalContent();

        $statusCode = $response->getStatusCode();


        if (!empty($response_content['error'])) {
  
            $this->fail('test_login_google() error: '.$response_content['error']);

        } else {

              
            if($statusCode == 200 || $statusCode == 201){

            print "\xA".'test_login_google() bearerToken: '.$response_content['data']['bearerToken'];

            $response->assertStatus($statusCode);

            }

        }          
          
       } else {

            print "\xA".'test_login_google skip';

            $this->assertTrue(true);

       }

    }

}
