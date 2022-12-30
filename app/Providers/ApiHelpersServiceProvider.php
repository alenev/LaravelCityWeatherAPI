<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ApiHelpersServiceProvider extends ServiceProvider
{
    protected $helpers = [
        'GoogleAuthHelper',
        'AuthHelper'
    ];

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
      
        foreach($this->helpers as $name){
          require_once(app_path().'/Helpers/'.$name.'.php');
        }

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
