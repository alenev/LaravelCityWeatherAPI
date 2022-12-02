@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                    
                    <router-view></router-view>

                         @auth
                            logged in   
                         @else
                            <div id="login_box">
                            <div id="google_login_button">
                            <div id="google_logo">
                            <img  src="img/google_logo.png" />
                            </div>
                            Don't Have An Account? <a>Sign up</a> or 
                             <a>Sign in</a>
                            </div>
                           </div>
                        @endauth
  
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
