<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redis;


class Weather extends Model
{
    use HasFactory;

    protected $table = 'Weather';
    protected $fillable = [
        'city',
        'data'
    ];

    public function redis_test(){
        try{
            $redis=Redis::connect('127.0.0.1',3306);
            return 1;
        }catch(\Predis\Connection\ConnectionException $e){
            return 0;
    }
    }


    public static function add($fields){

        $city = new static;
        $city->fill($fields);
        $city->save();

        $redis = $city->redis_test();
        if($redis == 1){
        $ucity = $fields['city'];
        Redis::del('city_'.$ucity, $ucity);
        $eucity = Weather::where('city', $ucity)->get();
        Redis::set('city_'.$ucity, $eucity);
        }

        return $city;
     }

        public function edit($fields){

        $this->fill($fields);
        $nowtime = Carbon::now();
        $nowtimeDB = Carbon::parse($nowtime)->format('Y-m-d H:i:s');
        $this->updated_at = $nowtimeDB;
        $this->save();

        $redis = $this->redis_test();
        if($redis == 1){
        $ucity = $fields['city'];
        Redis::del('city_'.$ucity, $ucity);
        $eucity = Weather::where('city', $ucity)->get();
        Redis::set('city_'.$ucity, $eucity);
        }
 
     }
}
