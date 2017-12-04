<?php

namespace App\Http\Controllers;

use Google_Client;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller {
    public function googleLogin(Request $request) {
        $google_redirect_url = route('glogin');
        $gClient = new Google_Client();
        $gClient->setApplicationName(config('services.google.app_name'));
        $gClient->setClientId(config('services.google.client_id'));
        $gClient->setClientSecret(config('services.google.client_secret'));
        $gClient->setRedirectUri($google_redirect_url);
        $gClient->setDeveloperKey(config('services.google.api_key'));
        $gClient->setScopes(array(
            'https://www.googleapis.com/auth/plus.me',
            'https://www.googleapis.com/auth/userinfo.email',
            'https://www.googleapis.com/auth/userinfo.profile',
        ));
        $google_oauthV2 = new \Google_Service_Oauth2($gClient);
        if ($request->get('code')) {
            $gClient->authenticate($request->get('code'));
            $token = $gClient->getAccessToken();
            $request->session()->put('token', $token['access_token']);
        }
        if ($gClient->getAccessToken()) {
            //For logged in user, get details from google using access token
            $guser = $google_oauthV2->userinfo->get();

            $request->session()->put('name', $guser['name']);
            if ($user = User::where('email', $guser['email'])->first()) {
                Auth::login($user);
            } else {
                $allowedDomain = config('services.google.apps_domain');
                $explodedEmail = explode('@', $guser['email']);
                $domain = array_pop($explodedEmail);
                if($allowedDomain != "" && $allowedDomain != $domain) {
                    echo "Invalid domain. Login using your " . $allowedDomain . " email.";
                    $request->session()->flush();
                    $gClient->revokeToken();
                    exit;
                } else {
                    $user = User::create([
                        'name' => $guser['name'],
                        'email' => $guser['email'],
                        'password' => "blah",
                    ]);
                    Auth::login($user);
                }
            }
            return redirect()->intended('experiments');
        } else {
            //For Guest user, get google login url
            $authUrl = $gClient->createAuthUrl();
            return redirect()->to($authUrl);
        }
    }
}