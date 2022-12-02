<?php

namespace App\Providers;
use Carbon\Carbon;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();


        Passport::tokensExpireIn(Carbon::now()->addMinutes(5));
        Passport::refreshTokensExpireIn(Carbon::now()->addMinutes(10));

        /*
        Passport::loadKeysFrom('/secret-keys/oauth');
        Passport::useClientModel(Client::class);
        Passport::useTokenModel(TokenModel::class);
        Passport::useAuthCodeModel(AuthCode::class);
        Passport::usePersonalAccessClientModel(PersonalAccessClient::class);
        */
        
    }
}
