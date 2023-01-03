<?php

namespace App\Helpers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Guzzle\Http\Exception\ClientErrorResponseException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Support\Facades\Redis;
use App\Models\Weather;

class CityWeatherHelper{

public static function openweathermap($data_geo)
{
    
        $url = 'https://api.openweathermap.org/data/2.5/weather?lat='.$data_geo["latitude"].'&lon='.$data_geo["longtitude"].'&units=metric&appid=ab616f96e7078ab6ec4b8876d0d08a5b';
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

public static function getCityFromRedis(string $ucity):object|null
{
    $redis_eucity = Redis::get('city_'.$ucity); 

    $val = (!empty($redis_eucity)) ? (object) json_decode($redis_eucity) : null;

    return $val;

}

public static function getCityFromDB(string $ucity):object|null
{
    $db_eucity = Weather::where('city', $ucity)->get()->first();

    return $db_eucity;

} 

}