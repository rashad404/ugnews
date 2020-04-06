<?php
namespace Controllers;

use Core\Controller;
use Core\Language;
use Google_Client;
use Google_Service_Oauth2;
use Facebook\Facebook;
use Helpers\Console;
use Helpers\Session;
use Helpers\Url;
use Models\AjaxModel;
use Models\AuthModel;
use Helpers\Curl;

/**
 * Sample controller showing a construct and 2 methods and their typical usage.
 */
class Auth extends Controller
{
    public $lng;
    public $userId;
    public $userInfo;
    // Call the parent construct
    public function __construct()
    {
        parent::__construct();
        $this->lng = new Language();
        $this->lng->load('app');
        $this->userId = intval(Session::get("user_session_id"));
        new AjaxModel();
    }


    public function facebook_login(){
        $fb = new Facebook([
            'app_id' => '602514563823433', // Replace {app-id} with your app id
            'app_secret' => 'dfa689f62219f6ae4111a2591a4a3dc3',
            'default_graph_version' => 'v3.2',
        ]);

        $helper = $fb->getRedirectLoginHelper();

        $permissions = ['email']; // Optional permissions
        $loginUrl = $helper->getLoginUrl('https://ureb.com/auth/facebook/callback', $permissions);

        echo '<a href="' . htmlspecialchars($loginUrl) . '">Log in with Facebook!</a>';

    }

    public function facebook_callback(){
        $app_id = '602514563823433';
        $app_secret = 'dfa689f62219f6ae4111a2591a4a3dc3';
        $fb = new Facebook([
            'app_id' => $app_id, // Replace {app-id} with your app id
            'app_secret' => $app_secret,
            'default_graph_version' => 'v3.2',
        ]);

        $helper = $fb->getRedirectLoginHelper();

        try {
            $accessToken = $helper->getAccessToken();
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        if (! isset($accessToken)) {
            if ($helper->getError()) {
                header('HTTP/1.0 401 Unauthorized');
                echo "Error: " . $helper->getError() . "\n";
                echo "Error Code: " . $helper->getErrorCode() . "\n";
                echo "Error Reason: " . $helper->getErrorReason() . "\n";
                echo "Error Description: " . $helper->getErrorDescription() . "\n";
            } else {
                header('HTTP/1.0 400 Bad Request');
                echo 'Bad request';
            }
            exit;
        }

        // Logged in

        // The OAuth 2.0 client handler helps us manage access tokens
        $oAuth2Client = $fb->getOAuth2Client();

        // Get the access token metadata from /debug_token
        $tokenMetadata = $oAuth2Client->debugToken($accessToken);


        // Validation (these will throw FacebookSDKException's when they fail)
        $tokenMetadata->validateAppId($app_id); // Replace {app-id} with your app id
        // If you know the user ID this access token belongs to, you can validate it here
        //$tokenMetadata->validateUserId('123');
        $tokenMetadata->validateExpiration();

        if (! $accessToken->isLongLived()) {
            // Exchanges a short-lived access token for a long-lived one
            try {
                $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
            } catch (Facebook\Exceptions\FacebookSDKException $e) {
                echo "<p>Error getting long-lived access token: " . $e->getMessage() . "</p>\n\n";
                exit;
            }
        }

        $_SESSION['fb_access_token'] = (string) $accessToken;

        try {
            // Returns a `Facebook\FacebookResponse` object
            $response = $fb->get('/me?fields=id,name,first_name,last_name,picture', $accessToken);
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }
        $user = $response->getGraphUser();

        $modelArray =AuthModel::facebook($user);

        if(empty($modelArray['errors'])){
            Url::redirect('');
            exit;
        }else {
            Session::setFlash('error',$modelArray['errors']);
            Url::redirect('login');
            exit;
        }
    }


    public function google(){
        $clientID = '358271044733-dkovkbpii2rt8ocr9ednfm9q9qmerqe4.apps.googleusercontent.com';
        $clientSecret = 'WeMXhqBBxwbBOF65qw0thv8T';
        $redirectUri = 'https://ureb.com/auth/google';

        // create Client Request to access Google API
        $client = new Google_Client();
        $client->setClientId($clientID);
        $client->setClientSecret($clientSecret);
        $client->setRedirectUri($redirectUri);
        $client->addScope("email");
        $client->addScope("profile");

        // authenticate code from Google OAuth Flow
        if (isset($_GET['code'])) {
            $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
            $client->setAccessToken($token['access_token']);
            // get profile info
            $google_oauth = new Google_Service_Oauth2($client);
            $user = $google_oauth->userinfo->get();

            $modelArray = AuthModel::google($user);
            if(empty($modelArray['errors'])){
                Url::redirect('');
                exit;
            }else {
                Session::setFlash('error',$modelArray['errors']);
                Url::redirect('login');
                exit;
            }

            Url::redirect('');
            // now you can use this profile info to create account in your website and make user logged in.
        } else {
            echo "<a href='".$client->createAuthUrl()."'>Google Login</a>";

        }
    }


}
