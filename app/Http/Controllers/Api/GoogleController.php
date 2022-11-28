<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller as Controller;

use App\Models\User;
require_once base_path().'/vendor/autoload.php';

class GoogleController extends Controller
{
    private function getClient()
    {
        $configJson = base_path().'/google_api_config.json';

        $applicationName = 'xyz';

        $client = new \Google\Client();
        $client->setApplicationName($applicationName);
        $client->setAuthConfig($configJson);
        $client->setAccessType('offline');
        $client->setApprovalPrompt ('force');

        $client->setScopes(
            [
                \Google\Service\Oauth2::USERINFO_PROFILE,
                \Google\Service\Oauth2::USERINFO_EMAIL,
                \Google\Service\Oauth2::OPENID,
            ]
        );
        $client->setIncludeGrantedScopes(true);
        return $client;
    }

    public function getAuthUrl(Request $request):JsonResponse
    {

        $client = $this->getClient();

        $authUrl = $client->createAuthUrl();

        return response()->json($authUrl, 200);
    }

    public function postLogin(Request $request):JsonResponse
    {


        $authCode = urldecode($request->input('auth_code'));
       // $authCode = urldecode($request->input('code'));

        $client = $this->getClient();

        $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);

        $client->setAccessToken(json_encode($accessToken));

        $service = new \Google\Service\Oauth2($client);
        $userFromGoogle = $service->userinfo->get();

        $user = User::where('provider_name', '=', 'google')
            ->where('provider_id', '=', $userFromGoogle->id)
            ->first();
    /*  $this->validate($userFromGoogle, [
                'name' => 'required',
                'email' => 'required|email|unique:users'
        ]);
    */

        if (!$user) {
            $user = User::create([
                    'provider_id' => $userFromGoogle->id,
                    'provider_name' => 'google',
                    'google_access_token_json' => json_encode($accessToken),
                    'name' => $userFromGoogle->name,
                    'email' => $userFromGoogle->email
               ]);
        } else {
            $user->google_access_token_json = json_encode($accessToken);
            $user->save();
        }


        $token = $user->createToken("Google")->accessToken;
        return response()->json($token, 201);
    }

    private function getUserClient()
    {

        $user = User::where('id', '=', auth()->guard('api')->user()->id)->first();

        $accessTokenJson = stripslashes($user->google_access_token_json);
        $client = $this->getClient();
        $client->setAccessToken($accessTokenJson);


        if ($client->isAccessTokenExpired()) {

            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            $client->setAccessToken($client->getAccessToken());
            $user->google_access_token_json = json_encode($client->getAccessToken());
            $user->save();
        }

        return $client;
    }

    public function getWeather(Request $request):JsonResponse
    {
      //  dd('getWeather');
        $client = $this->getUserClient();

        $AccessToken = json_encode($client->getAccessToken());

        return response()->json($AccessToken, 200);
    }

}
