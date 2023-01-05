<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Redis;
use Carbon\Carbon;

class APIHelper{


public static function redis_available():bool
{

    try{

       // $redis = Redis::connect('127.0.0.1',3306);
        $redis = Redis::connect('127.0.0.1',6379);

        return true;

    }catch(\Predis\Connection\ConnectionException $e){ 

        return false;
        
    }
}

public static function NeedUpdateDBitem(string $updated_at = null, int $update_period_seconds):bool
{

    $odate = Carbon::parse($updated_at);

    $ndate = Carbon::now();

    $ddate = $odate->diffInSeconds($ndate);
  

    ($ddate > $update_period_seconds) ? $e = true : $e = false;

    return $e;

}

public static function getNowTimeDBformat():string
{
    $nowtime = Carbon::now();

    return Carbon::parse($nowtime)->format('Y-m-d H:i:s');

}

public static function formatDateTimeToDBFormat(string $datetime):string
{
    return Carbon::parse($datetime)->format('Y-m-d H:i:s');
}


}