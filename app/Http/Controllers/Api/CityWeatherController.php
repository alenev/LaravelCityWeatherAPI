<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CityWeatherRequest;
use Illuminate\Http\JsonResponse;
use App\Helpers\DataHelper;
use App\Helpers\CityWeatherHelper;
use App\Models\Weather;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CityWeatherController extends Controller
{
    
    private string $userCity;
    private array  $openweathermap;    
    private object $openweathermap_data;
    private bool   $redisAvailable = false;
    private object $existUserCityData;
    private bool   $existUserCityDataInRedis = false;
    private bool   $existUserCityDataInDB = false;
    private bool   $userCityDataUpdate = false;
    private object $existUserCityDataFromDb;
    private array  $outputInfo;
    private array  $outputData;
 

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

        $this->userCity = $request['geo_city'];

        // check redis available
        $this->redisAvailable = DataHelper::redisAvailable();
   
        // check exist city weather data in redis
        if($this->redisAvailable){

            $redis_eucity = CityWeatherHelper::getCityFromRedis($this->userCity);

            if (!empty($redis_eucity))
            {
                $this->existUserCityDataInRedis = true;

                $this->existUserCityData = $redis_eucity->{0};

                $this->outputInfo['data_from'] = 'redis'; 

            }

        }

        // check exist city weather data in DB
        if(empty($this->existUserCityData))
        {

            $db_eucity = CityWeatherHelper::getCityFromDB($this->userCity);

            if(!empty($db_eucity))
            {
 
                $this->existUserCityDataInDB = true;

                $this->existUserCityData = $db_eucity;

                $this->outputInfo['city_from'] = 'DB'; 

            }

        } 

        // check needle city weather data update in redis/DB
        if(empty($this->existUserCityData) || (!empty($this->existUserCityData) && $this->userCityDataUpdate))
        {
            $this->userCityDataUpdate = true;

        }else{

            $this->userCityDataUpdate = DataHelper::needUpdateDBitem($this->existUserCityData->updated_at, env('CITY_WEATHER_UPDATE_PERIOD_SECONDS'));

        }

        $this->outputInfo['city_data_updated'] = 'false'; 

        // updating city weather data in redis/DB
        if($this->userCityDataUpdate)
        {
 
        // get new weather city data from Openweathermap API 
        $this->openweathermap = CityWeatherHelper::openweathermap($data_geo);
        $this->openweathermap_data = $this->openweathermap["data"]->main;

        $user = Auth::user();

        $this->outputData = array( 
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
    
        $this->outputData["main"] = array(
            
            "city" => $this->userCity

        );


        if(isset($this->openweathermap_data->temp)){
            $this->outputData["main"]["temp"] = round($this->openweathermap_data->temp, 0, PHP_ROUND_HALF_UP);
        }
        
        if(isset($this->openweathermap_data->pressure)){
            $this->outputData["main"]["pressure"] = $this->openweathermap_data->pressure;
        }
        
        if(isset($this->openweathermap_data->humidity)){
            $this->outputData["main"]["humidity"] = $this->openweathermap_data->humidity;
        }

        if(isset($this->openweathermap_data->temp_min)){
            if(!empty($this->openweathermap_data->temp_min)){
                $this->outputData["main"]["temp_min"] = round($this->openweathermap_data->temp_min, 0, PHP_ROUND_HALF_UP);   
            }else{
                $this->outputData["main"]["temp_min"] = 0;   
            }
        }
        
        if(isset($this->openweathermap_data->temp_max)){
            if(!empty($this->openweathermap_data->temp_max)){
               $this->outputData["main"]["temp_max"] = round($this->openweathermap_data->temp_max, 0, PHP_ROUND_HALF_UP); 
            }else{
               $this->outputData["main"]["temp_max"] = 0;
            }
        }

        $this->outputInfo['city_data_updated'] = 'true'; 

        $this->outputInfo['updated_at'] = DataHelper::getNowTimeDBformat();

        $this->outputData['info'] = $this->outputInfo; 
  
        }else{
            
            // get stored weather city data from redis/DB
            $data = json_decode($this->existUserCityData->data); 

            $adata = get_object_vars($data);

            $this->outputInfo['updated_at'] = DataHelper::formatDateTimeToDBFormat($this->existUserCityData->updated_at);

            $adata['info'] = $this->outputInfo; 

            $this->outputData = (object) $adata;
        }



        $nucity = [
            "city" => $this->userCity, 
            "data" => json_encode($this->outputData)
        ];


        if(!$this->existUserCityDataInRedis && !$this->existUserCityDataInDB){

            $this->existUserCityDataFromDb = Weather::add($nucity);

        }else{


            $existUserCityDataFromDb = Weather::where('city', $this->existUserCityData->city)->get()->first();

            if(!empty($existUserCityDataFromDb)){

                $this->existUserCityDataFromDb = $existUserCityDataFromDb;

            }else{

                $this->existUserCityDataFromDb = Weather::add($nucity);
                
            }

            if(!empty($this->existUserCityDataFromDb)){

                $this->existUserCityDataFromDb->edit($nucity);

            }else{

                $this->existUserCityDataFromDb = Weather::add($nucity);   
            }

        }  


    return Controller::apiResponceSuccess($this->outputData, 200);

    }  

}
