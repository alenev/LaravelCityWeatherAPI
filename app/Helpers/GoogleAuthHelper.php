<?php
namespace app\Helpers;

require_once base_path().'/vendor/autoload.php';
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class GoogleAuthHelper {

    public static function getGoogleClient(?string $GoogleClientRedirectUrl = null):Object
    {

        $configJson = base_path().'/google_api_config.json';

        $applicationName = env('APP_NAME');

        $googleClient = new \Google\Client();

        $googleClient->setApplicationName($applicationName);

        $googleClient->setAuthConfig($configJson);

        if (!empty($GoogleClientRedirectUrl)) {

            $googleClient->setRedirectUri($GoogleClientRedirectUrl);
            
        }

        $googleClient->setAccessType('offline');

        $googleClient->setApprovalPrompt ('force');

        $googleClient->setScopes(
            [
                \Google\Service\Oauth2::USERINFO_PROFILE,
                \Google\Service\Oauth2::USERINFO_EMAIL,
                \Google\Service\Oauth2::OPENID,
            ]
        );

        $googleClient->setIncludeGrantedScopes(true);

        return $googleClient;
    }


    public static function getGoogleAuthUrl(?string $GoogleClientRedirectUrl = null):string|array
    {


        $googleClient = GoogleAuthHelper::getGoogleClient($GoogleClientRedirectUrl);

        $googleAuthUrl = $googleClient->createAuthUrl();
        
        if(empty($googleAuthUrl)){

           return ['error' => 'get google auth url problem', 'status' => 503];
      
        }

        return $googleAuthUrl;
        
    }

    
    public static function GoogleAuthCodeToAccesToken(?string $AuthCode):array|object
    {   

        $googleClient = GoogleAuthHelper::getGoogleClient();

        if (!$googleClient){

            return ['error' => 'google client false', 'status' => 503];

        }

        $accessToken = $googleClient->fetchAccessTokenWithAuthCode($AuthCode);

        $accessTokenInfo = json_decode(json_encode($accessToken));

        if(!empty($accessTokenInfo->error)){
            
          return ['error' => 'invalid_grant', 'status' => 406];

        }
        
        $googleClient->setAccessToken(json_encode($accessToken));

        return $googleClient;
    }

    public static function GoogleLoginRequestValidate($request)
    {

        $validator = Validator::make($request->all(), [
            'auth_code' => 'required|string|min:2',
            'remember' => [Rule::in([0,1])]
        ]);

        return $validator;
        
    }

    public static function UserFromGoogleUpdateOrRegister(object $userFromGoogle):object|bool
    {
        $user = User::where('provider_name', '=', 'google')
        ->where('provider_id', '=', $userFromGoogle->id)
        ->first();

        $avatarImg = file_get_contents($userFromGoogle->picture);

        $avatarImgSize = getimagesize($userFromGoogle->picture);

        $avatarImgExtension = image_type_to_extension($avatarImgSize[2]);

    $uploadAvatar = null;

    if (!$user) 
    {

        $user = User::create([
                'provider_id' => $userFromGoogle->id,
                'provider_name' => 'google',
                'google_access_token_json' => json_encode($userFromGoogle->AccessToken),
                'name' => $userFromGoogle->name,
                'email' => $userFromGoogle->email,
                'first_name' => $userFromGoogle->givenName,
                'last_name' => $userFromGoogle->familyName
           ]);

        $uploadAvatar = $user->uploadAvatar($avatarImg, $avatarImgExtension);
        
        $user->event = 'register';

            
    }else{

        $user->google_access_token_json = json_encode($userFromGoogle->AccessToken);

        $user->first_name = $userFromGoogle->givenName;

        $user->last_name = $userFromGoogle->familyName;

        $user->event = 'login';

    }

    if ($user) {

        return $user;
        
    }else{

        return false;
    }

    }
}