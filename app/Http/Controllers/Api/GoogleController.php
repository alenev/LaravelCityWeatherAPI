<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller as Controller;
use Carbon\Carbon;
use App\Models\User;
use Laravel\Passport\Passport;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Guzzle\Http\Exception\ClientErrorResponseException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\BadResponseException;
use App\Models\Weather;
use Illuminate\Support\Facades\Redis;

require_once base_path().'/vendor/autoload.php';

class GoogleController extends Controller
{
    private function getClient()
    {
        $configJson = base_path().'/google_api_config.json';

        $applicationName = 'xyz';

        $client = new \Google\Client();
        $client->setApplicationName($applicationName);
        $client->setAuthConfig($configJson);
        $client->setAccessType('offline');
        $client->setApprovalPrompt ('force');

        $client->setScopes(
            [
                \Google\Service\Oauth2::USERINFO_PROFILE,
                \Google\Service\Oauth2::USERINFO_EMAIL,
                \Google\Service\Oauth2::OPENID,
            ]
        );
        $client->setIncludeGrantedScopes(true);
        return $client;
    }

    public function getAuthUrl(Request $request):JsonResponse
    {

        $client = $this->getClient();

        $authUrl = $client->createAuthUrl();

        return response()->json($authUrl, 200);
    }

    public function GoogleLogin(Request $request):JsonResponse
    {

        $authCode = $request['auth_code'];
        $remember = $request['remember'];

        if($remember == '1'){
            $token_exp = 60;
        }else{
            $token_exp = 3;
        }


        Passport::personalAccessTokensExpireIn(now()->addMinutes($token_exp));
        Passport::tokensExpireIn(now()->addMinutes($token_exp));

        $client = $this->getClient();

        $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);

        $accessTokenInfo = json_decode(json_encode($accessToken));
        if(!empty($accessTokenInfo->error)){
          return response()->json( ['error' => 'invalid_grant']);
        }

        $client->setAccessToken(json_encode($accessToken));

        $service = new \Google\Service\Oauth2($client);
        $userFromGoogle = $service->userinfo->get();

        $user = User::where('provider_name', '=', 'google')
            ->where('provider_id', '=', $userFromGoogle->id)
            ->first();

            $avatar_img = file_get_contents($userFromGoogle->picture);
            $avatar_img_size = getimagesize($userFromGoogle->picture);
            $avatar_img_extension = image_type_to_extension($avatar_img_size[2]);

        if (!$user) {

            $user = User::create([
                    'provider_id' => $userFromGoogle->id,
                    'provider_name' => 'google',
                    'google_access_token_json' => json_encode($accessToken),
                    'name' => $userFromGoogle->name,
                    'email' => $userFromGoogle->email,
                    'first_name' => $userFromGoogle->givenName,
                    'last_name' => $userFromGoogle->familyName
               ]);

            $user->uploadAvatar($avatar_img, $avatar_img_extension);

               
        }else{
            $user->google_access_token_json = json_encode($accessToken);
            $user->first_name = $userFromGoogle->givenName;
            $user->last_name = $userFromGoogle->familyName;
        }

        $token = $user->createToken("Google")->accessToken;
       
        return response()->json($token, 201);

    }

    public function login(Request $request)
    {
        return response()->json($request->user());
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
  
    public function user(Request $request)
    {

        return response()->json($request->user());
    }

    private function getUserClient()
    {

        $user = User::where('id', '=', auth()->guard('api')->user()->id)->first();

        $accessTokenJson = stripslashes($user->google_access_token_json);
        $client = $this->getClient();
        $client->setAccessToken($accessTokenJson);


        if ($client->isAccessTokenExpired()) {

            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            $client->setAccessToken($client->getAccessToken());
            $user->google_access_token_json = json_encode($client->getAccessToken());
            $user->save();
        }

        return $client;
    }


    public function redis_test(){
        try{
            $redis=Redis::connect('127.0.0.1',3306);
            return 1;
        }catch(\Predis\Connection\ConnectionException $e){
            return 0;
    }
    }


    public function openweathermap($url){

        $client = new \GuzzleHttp\Client();
        $res = $client->get($url, []);
        $status = $res->getStatusCode(); 
        $ans = $res->getBody();
        $data = json_decode($ans);

        $output = array(
            "status" => $status,
            "ans" => $ans,
            "data" => $data
        );

        return $output;
    }

    public function getWeather(Request $request):JsonResponse
    {

       $data_geo_arr = array(
        "latitude" => $request["geo_latitude"],
        "longitude" => $request["geo_longitude"],
        "city" => $request["geo_city"]
       );

       $data_geo = (object)($data_geo_arr);


        if(empty($data_geo)){

            return response()->json(["error" => "geodata unavailable"]); 

        }
       
        $latitude = $data_geo->latitude;
        $longitude = $data_geo->longitude;
        $openweathermap_url = 'https://api.openweathermap.org/data/2.5/weather?lat='.$latitude.'&lon='.$longitude.'&units=metric&appid=ab616f96e7078ab6ec4b8876d0d08a5b';
        $openweathermap_data = null;

        if(empty($data_geo->city)){
            $openweathermap_data = $this->openweathermap($openweathermap_url);
            $location = $openweathermap_data["data"]->name;
        }else{
            $location = $data_geo->city;
        }
        

        $ucity = $location;
        $get_db_city = false;
        $city_update = false;
        $ddate = null;
        $eucity = null;
        $added_nucity = null;
        $from_src = null;
        $redis = $this->redis_test();


        if ($redis == 1) {

        $cashed_eucity = Redis::get('city_'.$ucity); 
        
        if (!empty($cashed_eucity)) {

        $eucitys = json_decode($cashed_eucity);
        $eucity = (object) $eucitys[0]; 

        } else {

        $get_db_city = true;
        }

        }


        if($get_db_city) {

            $eucityd = Weather::where('city', $ucity)->get();

            if(count($eucityd) != 0){
 
            $eucity = $eucityd[0];

              if($redis == 1){
                 Redis::set('city_'.$ucity, $eucity);
              }
            }

        }
   
        $nowtime = Carbon::now();

        if(!empty($eucity)){

        $ddate = null;
        $odate = Carbon::parse($eucity->updated_at);
        $ndate = Carbon::now();
        $ddate = $odate->diffInSeconds($ndate);
        $nowtimeDB = Carbon::parse($nowtime)->format('Y-m-d H:i:s');

         if($ddate > 60){
            $city_update = true;
         }

        }


        if(empty($eucity) || $city_update){

           if(empty($openweathermap_data)){

           $openweathermap_data = $this->openweathermap($openweathermap_url);
            
           }  
           $data = $openweathermap_data["data"];

        }else{

           $datas = json_decode($eucity->data); 
           $data = (object) $datas;

        }
   
        $tempR = round($data->main->temp, 0, PHP_ROUND_HALF_UP);
        $temp_minR = round($data->main->temp_min, 0, PHP_ROUND_HALF_UP); 
        $temp_maxR = round($data->main->temp_max, 0, PHP_ROUND_HALF_UP); 

        $user = Auth::user();
        ($user->status == 1) ? $ustatus = 'Active' : $ustatus = 'Disabled';
        $odataa = array(
            "user" => array(
              "id" => $user->id,
              "first_name" => $user->first_name,
              "last_name" => $user->last_name,
              "email" => $user->email,
              "profile" => 'http://localhost:8000/uploads/avatars/'.$user->profile,
              "status" => $ustatus,
              "created_at" => $user->created_at,
              "updated_at" => $user->updated_at
            ),
            "main" => array(
               "temp" => $tempR,
               "pressure" => $data->main->pressure,
               "humidity" => $data->main->humidity,
               "temp_min" => $temp_minR,
               "temp_max" =>  $temp_maxR,
               "city" => $ucity,
               "test" => "temp: ".$data->main->temp." temp_min: ".$data->main->temp_min." temp_max: ".$data->main->temp_max

            )
        );
        
        $nucity = [
            "city" => $ucity, 
            "data" => json_encode($odataa)  
        ];

        if(empty($eucity)){

            $added_nucity = Weather::add($nucity);


        }else if($city_update){

            $city = Weather::find($eucity->id);
            $city->edit($nucity);

        }  

        $cities = Weather::all();
     
    return response()->json($odataa);

   }

}
