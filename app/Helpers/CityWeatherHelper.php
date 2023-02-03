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

public static function openweathermap($geoData):array
{
    
        $url = 'https://api.openweathermap.org/data/2.5/weather?lat='.$geoData["latitude"].'&lon='.$geoData["longtitude"].'&units=metric&appid=ab616f96e7078ab6ec4b8876d0d08a5b';
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

public static function getCityFromRedis(string $userCity):object|null
{
    $redisUserCity = Redis::get('city_'.$userCity); 

    $data = (!empty($redisUserCity)) ? (object) json_decode($redisUserCity) : null;

    return $data;

}

public static function getCityFromDB(string $userCity):object|null
{
    $data = Weather::where('city', $userCity)->get()->first();

    return $data;

} 

}