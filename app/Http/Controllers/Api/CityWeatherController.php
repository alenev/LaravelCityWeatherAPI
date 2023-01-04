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

class CityWeatherController extends Controller
{
    
    private string $ucity;
    private bool $redis_available = false;
    private object $eucity;
    private bool $eucity_from_redis = false;
    private bool $eucity_from_DB = false;
    private bool $city_update = false;
    private array $odataa;
    private object $db_city;
    private array $debug;

 

    public function index(CityWeatherRequest $request):JsonResponse
    {
       
    
        $data_geo = array(
          "latitude" => $request["geo_latitude"],
          "longtitude" => $request["geo_longitude"]
        );

        $this->ucity = $request['geo_city'];

        // check redis available
        $this->redis_available = APIHelper::redis_available();
   
        // check exist city weather data in redis
        if($this->redis_available){

            $redis_eucity = CityWeatherHelper::getCityFromRedis($this->ucity);

            if (!empty($redis_eucity))
            {
                $this->eucity_from_redis = true;
                $this->eucity = $redis_eucity->{0};

            }

        }

        // check exist city weather data in DB
        if(empty($this->eucity))
        {

            $db_eucity = CityWeatherHelper::getCityFromDB($this->ucity);

            if(!empty($db_eucity))
            {
 
                $this->eucity_from_DB = true;
                $this->eucity = $db_eucity;

                $debug['city_from'] = 'DB'; 

            }

        } 


        // check needle city weather data update in redis/DB
        if(empty($this->eucity) || (!empty($this->eucity) && $this->city_update))
        {
            $this->city_update = true;

        }else{

            $this->city_update = APIHelper::NeedUpdateDBitem($this->eucity->updated_at, env('CITY_WEATHER_UPDATE_PERIOD_SECONDS'));

        }

        
        // updating city weather data in redis/DB
        if($this->city_update)
        {
            

        // get new weather city data from Openweathermap API 
        $openweathermap = CityWeatherHelper::openweathermap($data_geo);
        $openweathermap_data = $openweathermap["data"]->main;

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


        if(!empty($openweathermap_data->temp)){
            $odataa["main"]["temp"] = round($openweathermap_data->temp, 0, PHP_ROUND_HALF_UP);
        }
        
        if(!empty($openweathermap_data->pressure)){
            $odataa["main"]["pressure"] = $openweathermap_data->pressure;
        }
        
        if(!empty($openweathermap_data->humidity)){
            $odataa["main"]["humidity"] = $openweathermap_data->humidity;
        }

        if(!empty($openweathermap_data->temp_min)){
            $odataa["main"]["temp_min"] = round($openweathermap_data->temp_min, 0, PHP_ROUND_HALF_UP);   
        }
        
        if(!empty($openweathermap_data->temp_max)){
            $odataa["main"]["temp_max"] = round($openweathermap_data->temp_max, 0, PHP_ROUND_HALF_UP); 
        }
  
        }else{
            
            // get stored weather city data from redis/DB
            $data = json_decode($this->eucity->data); 

            $odataa = (object) $data;
        }



        $nucity = [
            "city" => $this->ucity, 
            "data" => json_encode($odataa)
        ];

        if(!$this->eucity_from_redis && !$this->eucity_from_DB){

            $this->db_city = Weather::add($nucity);

        }else{

            $this->db_city= Weather::find($this->eucity->id);

            if(!empty($this->db_city)){

                $this->db_city->edit($nucity);

            }else{

                $this->db_city = Weather::add($nucity);   
            }

        }  


    return Controller::ApiResponceSuccess($odataa, 200);

    }  

}
