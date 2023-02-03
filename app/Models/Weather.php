<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redis;
use App\Helpers\DataHelper;

class Weather extends Model
{
    use HasFactory;

    protected $table = 'weather';
    protected $primaryKey = 'id';
    protected $fillable = [
        'city',
        'data'
    ];


    public static function add($fields):bool|object
    {

        $city = new static;
        $city->fill($fields);
        $city->save();

    
        if(DataHelper::redisAvailable()){


        if($city->redisStorageUpdate($fields['city'])){

            return $city;

        }else{

            return false;

        }
     }

     return $city;

}

        public function edit($fields):bool
        {

        $this->fill($fields);

        $this->updated_at = DataHelper::getNowTimeDBformat();

        $this->save();


        if(DataHelper::redisAvailable()){


        if($this->redisStorageUpdate($fields['city'])){

            return true;

        }else{

            return false;

        }

        }

        return true;
 
     }

     protected function redisStorageUpdate(string $city):bool

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
