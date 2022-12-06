@extends('layouts.main')

@section('content')
<div class="relative flex items-top justify-center min-h-screen sm:items-center py-4 sm:pt-0">


<div class="max-w-6xl mx-auto sm:px-6 lg:px-8">


    <div class="mt-8 sm:rounded-lg">
        <div id="login_box">
          
                @auth
                logged in   
                @else
                <div id="google_login_button">
                <div id="google_logo">
                <img  src="img/google_logo.png" />
                </div>
                Don't Have An Account? <a>Sign up</a> or 
                 <a>Sign in</a>
                </div>
        
                <div id="remember">
                 <input type="checkbox" id="remember" name="remember" value="yes" />
                Remeber for 1 hour? (default 3 min)    
                </div>

                @endauth

              
        </div>

        <div id="logged_in_box">
            <div id="weather_UI">
            <div id="weather_UI_main">
            <div id="weather_UI_city"></div>
            <div id="weather_UI_temp"></div>
            </div>
            <div id="weather_UI_other">
            <div id="weather_UI_pressure"></div>   
            <div id="weather_UI_humidity"></div>
            <div id="weather_UI_temp_min"></div>
            <div id="weather_UI_temp_max"></div>
            </div>
            </div>

            <div id="logout_box">
            <a>Logout</a>
           </div>

        </div>

    </div>


</div>
</div>
@endsection