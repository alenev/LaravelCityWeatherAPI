<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redis;
use App\Helpers\APIHelper;

class Weather extends Model
{
    use HasFactory;

    protected $table = 'weather';
    protected $fillable = [
        'city',
        'data'
    ];


    public static function add($fields):bool
    {

        $city = new static;
        $city->fill($fields);
        $city->save();

    
        if(APIHelper::redis_available()){


        if($city->redis_storage_update($fields['city'])){

            return $city;

        }else{

            return false;

        }
     }

}

        public function edit($fields):bool
        {

        $this->fill($fields);

        $nowtime = Carbon::now();

        $nowtimeDB = Carbon::parse($nowtime)->format('Y-m-d H:i:s');

        $this->updated_at = $nowtimeDB;

        $this->save();


        if(APIHelper::redis_available()){


        if($this->redis_storage_update($fields['city'])){

            return true;

        }else{

            return false;

        }

        }
 
     }

     protected function redis_storage_update(string $city):bool

     {
        if(Redis::get('city_'.$city) && !Redis::del('city_'.$city)){

            return false;

        }

        $eucity = Weather::where('city', $city)->get();

        if(Redis::set('city_'.$city, $eucity)){

            return true;

        }
        
     }
}
