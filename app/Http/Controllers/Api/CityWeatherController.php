<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CityWeatherRequest;
use Illuminate\Http\JsonResponse;
use App\Helpers\APIHelper;
use App\Helpers\CityWeatherHelper;
use App\Models\Weather;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CityWeatherController extends Controller
{
    
    private string $ucity;
    private bool $redisAvailable = false;
    private object $eucity;
    private bool $eucityFromRedis = false;
    private bool $eucityFromDB = false;
    private bool $cityUpdate = false;
    private array $odataa;
    private object $dbCity;
    private array $info;
    private array $openweathermap;    
    private object $openweathermap_data;

 

    public function index(CityWeatherRequest $request):JsonResponse
    {
       if (isset($request->validator) && $request->validator->fails()) { 
 
          $validation_errors = $request->validator->errors()->messages();
		  
          $validation_errors_first = current((array)$validation_errors);
		  
          return Controller::apiResponceError($validation_errors_first[0], 404); 

       }
    
        $data_geo = array(
          "latitude" => $request["geo_latitude"],
          "longtitude" => $request["geo_longitude"]
        );

        $this->ucity = $request['geo_city'];

        // check redis available
        $this->redisAvailable = APIHelper::redisAvailable();
   
        // check exist city weather data in redis
        if($this->redisAvailable){

            $redis_eucity = CityWeatherHelper::getCityFromRedis($this->ucity);

            if (!empty($redis_eucity))
            {
                $this->eucityFromRedis = true;

                $this->eucity = $redis_eucity->{0};

                $this->info['data_from'] = 'redis'; 

            }

        }

        // check exist city weather data in DB
        if(empty($this->eucity))
        {

            $db_eucity = CityWeatherHelper::getCityFromDB($this->ucity);

            if(!empty($db_eucity))
            {
 
                $this->eucityFromDB = true;

                $this->eucity = $db_eucity;

                $this->info['city_from'] = 'DB'; 

            }

        } 

        // check needle city weather data update in redis/DB
        if(empty($this->eucity) || (!empty($this->eucity) && $this->cityUpdate))
        {
            $this->cityUpdate = true;

        }else{

            $this->cityUpdate = APIHelper::needUpdateDBitem($this->eucity->updated_at, env('CITY_WEATHER_UPDATE_PERIOD_SECONDS'));

        }

        $this->info['city_data_updated'] = 'false'; 

        // updating city weather data in redis/DB
        if($this->cityUpdate)
        {
 
        // get new weather city data from Openweathermap API 
        $this->openweathermap = CityWeatherHelper::openweathermap($data_geo);
        $this->openweathermap_data = $this->openweathermap["data"]->main;

        $user = Auth::user();

        $odataa = array( 
            "user" => array(
                "id" => $user->id,
                "first_name" => $user->first_name,
                "last_name" => $user->last_name,
                "email" => $user->email,
                "profile" => 'http://localhost:8000/uploads/avatars/' . $user->profile,
                "status" => ($user->status == 1) ? 'Active' : 'Disabled',
                "created_at" => $user->created_at,
                "updated_at" => $user->updated_at
            )
        );
    
        $odataa["main"] = array(
            
            "city" => $this->ucity

        );


        if(isset($this->openweathermap_data->temp)){
            $odataa["main"]["temp"] = round($this->openweathermap_data->temp, 0, PHP_ROUND_HALF_UP);
        }
        
        if(isset($this->openweathermap_data->pressure)){
            $odataa["main"]["pressure"] = $this->openweathermap_data->pressure;
        }
        
        if(isset($this->openweathermap_data->humidity)){
            $odataa["main"]["humidity"] = $this->openweathermap_data->humidity;
        }

        if(isset($this->openweathermap_data->temp_min)){
            if(!empty($this->openweathermap_data->temp_min)){
                $odataa["main"]["temp_min"] = round($this->openweathermap_data->temp_min, 0, PHP_ROUND_HALF_UP);   
            }else{
                $odataa["main"]["temp_min"] = 0;   
            }
        }
        
        if(isset($this->openweathermap_data->temp_max)){
            if(!empty($this->openweathermap_data->temp_max)){
               $odataa["main"]["temp_max"] = round($this->openweathermap_data->temp_max, 0, PHP_ROUND_HALF_UP); 
            }else{
               $odataa["main"]["temp_max"] = 0;
            }
        }

        $this->info['city_data_updated'] = 'true'; 

        $this->info['updated_at'] = ApiHelper::getNowTimeDBformat();

        $odataa['info'] = $this->info; 
  
        }else{
            
            // get stored weather city data from redis/DB
            $data = json_decode($this->eucity->data); 

            $adata = get_object_vars($data);

            $this->info['updated_at'] = ApiHelper::formatDateTimeToDBFormat($this->eucity->updated_at);

            $adata['info'] = $this->info; 

            $odataa = (object) $adata;
        }



        $nucity = [
            "city" => $this->ucity, 
            "data" => json_encode($odataa)
        ];


        if(!$this->eucityFromRedis && !$this->eucityFromDB){

            $this->dbCity = Weather::add($nucity);

        }else{


            $dbCity = Weather::where('city', $this->eucity->city)->get()->first();

            if(!empty($dbCity)){

                $this->dbCity = $dbCity;

            }else{

                $this->dbCity = Weather::add($nucity);
                
            }

            if(!empty($this->dbCity)){

                $this->dbCity->edit($nucity);

            }else{

                $this->dbCity = Weather::add($nucity);   
            }

        }  


    return Controller::apiResponceSuccess($odataa, 200);

    }  

}
