<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Redis;
use Carbon\Carbon;

class DataHelper{


public static function redisAvailable():bool
{

    try{

        $redis = Redis::connect(env('REDIS_HOST'), env('REDIS_PORT'));
        
        return true;

    }catch(\Predis\Connection\ConnectionException $e){ 

        return false;
        
    }
}

public static function needUpdateDBitem(string $updated_at = null, int $update_period_seconds):bool
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