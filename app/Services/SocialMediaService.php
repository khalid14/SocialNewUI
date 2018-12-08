<?php
/**
 * Created by PhpStorm.
 * User: Umer
 * Date: 3/8/2018
 * Time: 02:23 PM
 */

namespace App\Services;

use App\Providers\LaravelPersistentDataHandler;
use App\Providers\LaravelPersistentDataHandlerProvider;
use Facebook\Facebook;
use Illuminate\Http\Request;
use App\Contracts\SocialMediaRepository;
use App\Contracts\ShopRepository;
use Abraham\TwitterOAuth\TwitterOAuth;
use DirkGroenen\Pinterest\Pinterest;


class SocialMediaService
{


    /**
     * @var SocialMediaRepository;
     */
    protected $socialMediaRepository;

    /**
     * @var ShopRepository
     */
    protected $shopRepository;

    /**
     * SettingsController constructor.
     *
     * @param SocialMediaRepository $socialMediaRepository
     */
    public function __construct(SocialMediaRepository $socialMediaRepository, ShopRepository $shopRepository)
    {
        $this->socialMediaRepository = $socialMediaRepository;
        $this->shopRepository = $shopRepository;
    }

    public function socialLoginLinks(Request $request){

        $socialLinksArray = array();
        // first get shop id and then its social saved data
        $shop = $request->session()->get('shop');
        $shop = $this->shopRepository->findByField('name', $shop)->first();
        $socialMediaData =  $this->socialMediaRepository->findByField('shop_id', $shop->id)->first();
        if(!is_null($socialMediaData) && !is_null($socialMediaData->social)) {
            $socialMediaArray = json_decode($socialMediaData->social);
            // set facebook access token in session
            if (isset($socialMediaArray->facebook)) {
                $fbAccessToken = unserialize($socialMediaArray->facebook->access_token);
                $request->session()->put('fb_access_token', $fbAccessToken);
            }
            // set twitter access token in session
            if (isset($socialMediaArray->twitter)) {
                $twitterAccessToken = unserialize($socialMediaArray->twitter->access_token);
                $request->session()->put('twitter_access_token', $twitterAccessToken);
            }
            // set pinterest access token in session
            if (isset($socialMediaArray->pinterest)) {
                $pinAccessToken = unserialize($socialMediaArray->pinterest->access_token);
                $request->session()->put('pinterest_access_token', $pinAccessToken);
            }
        }

        $socialLink = '';
        // login logout url for pinterest
        $pinterest = new Pinterest(env('PINTEREST_APP_ID'), env('PINTEREST_APP_SECRET'));
        $loginLink = $pinterest->auth->getLoginUrl(env('APP_URL')  . '/pin-call-back/', array('write_public', 'read_public'));
        if (isset($pinAccessToken) && $pinAccessToken == 'aaa'){
            $pinterest->auth->setOAuthToken($pinAccessToken);
            $me = $pinterest->users->me();
ma           // $board = $pinterest->users->getMeBoards(
//            dd($board);
//            //checksum
//            $pinterest->pins->create(array(
//                "note"          => "Test board from API",
//                "image_url"     => "https://download.unsplash.com/photo-1438216983993-cdcd7dea84ce",
//                "board"         => "umeransarigee/checksum/"
//            ));

            if (!empty($me)){
                $profile =  json_decode($me);
                $socialLink = $profile->first_name;
                $logOutUrl = env('APP_URL')  . '/pin-call-back?logout=true';
                $socialLink .= " <span><a class='social-logout-pop' href='" . $logOutUrl . "'> Logout</span>";
            }
        }
        $socialLinksArray[] = ['loginLink' => $loginLink,'logoutLink' => $socialLink, 'platform' => 'pinterest'];
        // login url for twitter
        $connection = new TwitterOAuth(env('TWITTER_APP_ID'), env('TWITTER_APP_SECRET'));
        $request_token = $connection->oauth('oauth/request_token', array('oauth_callback' => env('APP_URL').'/twit-call-back/'));
        $request->session()->put('oauth_token', $request_token['oauth_token']);
        $request->session()->put('oauth_token_secret', $request_token['oauth_token_secret']);
        $loginLink = $connection->url('oauth/authorize', array('oauth_token' => $request_token['oauth_token']));
        if (isset($twitterAccessToken) && !is_null($twitterAccessToken)){
            // logout url for twitter
            // Now that we have an Access Token, we can discard the Request Token
            if($twitterAccessToken){
                // twitter posting code
                $connection = new TwitterOAuth(env('TWITTER_APP_ID'), env('TWITTER_APP_SECRET'), $twitterAccessToken['oauth_token'], $twitterAccessToken['oauth_token_secret']);
                $connection->post("statuses/update", ["status" => "posting checkout"]);
                $socialLink = $twitterAccessToken['screen_name'];
                $logOutUrl = env('APP_URL')  . '/twit-call-back?logout=true';
                $socialLink .= " <span><a class='social-logout-pop' href='" . $logOutUrl . "'> Logout</span>";
            }
        }
        $socialLinksArray[] = ['loginLink' => $loginLink,'logoutLink' => $socialLink, 'platform' => 'twitter'];
        // login url for facebook
        if (isset($fbAccessToken) && $fbAccessToken !='') {
            $fb = new Facebook([
                'app_id' => env('FACEBOOK_APP_ID'),
                'app_secret' => env('FACEBOOK_APP_SECRET'),
                'default_graph_version' => 'v2.8',
                'persistent_data_handler' =>  new \App\Services\LaravelPersistentDataHandlerProvider(),
                'default_access_token' =>$fbAccessToken
            ]);
        } else {
            $fb = new Facebook([
                'app_id' => env('FACEBOOK_APP_ID'),
                'app_secret' => env('FACEBOOK_APP_SECRET'),
                'default_graph_version' => 'v2.8',
                'persistent_data_handler' => new \App\Services\LaravelPersistentDataHandlerProvider()
                //'default_access_token' => '{access-token}', // optional
            ]);
        }

        $helper = $fb->getRedirectLoginHelper();
        $permissions = ['email', 'public_profile', 'user_posts', 'manage_pages', 'publish_pages'];
        $loginLink = $loginUrl = $helper->getLoginUrl(env('APP_URL') . '/fb-call-back', $permissions);
        $socialLink = '';
        try {
            if (isset($fbAccessToken) && $fbAccessToken !='') {
                $oResponse = $fb->get('/me?fields=id,name,email', $fbAccessToken);
                $me = $oResponse->getGraphUser();
                $socialLink = $me->getName();
                $logOutUrl = env('APP_URL')  . '/fb-call-back?logout=true';
                $socialLink .= " <span><a  class ='social-logout-pop' href='" . $logOutUrl . "'>Logout</span>";
            }
        } catch (\Facebook\Exceptions\FacebookResponseException $e) {
            if ($e->getErrorType() == "OAuthException") {
                $loginLink = htmlspecialchars($loginUrl);
            } else {
                // When Graph returns an error
                echo 'Graph returned an error: ' . $e->getMessage();
                exit;
            }
        } catch (\Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }
        $socialLinksArray[] = ['loginLink' => $loginLink,'logoutLink' => $socialLink,  'platform' => 'facebook'];

        return $socialLinksArray;
    }

    public function pinCallBack(Request $request){


        $pinterest = new Pinterest(env('PINTEREST_APP_ID'), env('PINTEREST_APP_SECRET'));
        $loginLink = $pinterest->auth->getLoginUrl(env('APP_URL')  . '/pin-call-back/', array('write_public','read_public'));

        if (!is_null($request->get('logout'))){
            $request->session()->forget('pin_access_token');
            // remove from database,session
            $shop = $request->session()->get('shop');
            $shop = $this->shopRepository->findByField('name', $shop)->first();
            $socialMediaData =  $this->socialMediaRepository->findByField('shop_id', $shop->id)->first();
            if(!is_null($socialMediaData) && !is_null($socialMediaData->social)) {
                $socialMediaArray = json_decode($socialMediaData->social, TRUE);
                $socialMediaArray['pinterest']['access_token'] = '';
                $this->socialMediaRepository->saveAccessToken($request, json_encode($socialMediaArray));
            }
            return view('social-logout', [
                'loginLink' => $loginLink,
                'platform' => 'pinterest'
            ]);
        }

        if(isset($request["code"])){
            $token = $pinterest->auth->getOAuthToken($request["code"]);
            $pinterestAccessToken = $token->access_token;
            $pinterest->auth->setOAuthToken($pinterestAccessToken);
            $request->session()->put('pin_access_token', $pinterestAccessToken);
            $me = $pinterest->users->me();
            //dd($me);
            if (isset($pinterestAccessToken) && $pinterestAccessToken != ''){
                if (!empty($me)){
                    $profile =  json_decode($me);
                    $socialLink = $profile->first_name;
                    $logOutUrl = env('APP_URL')  . '/pin-call-back?logout=true';
                    $socialLink .= " <span><a class='social-logout-pop' href='" . $logOutUrl . "'> Logout</span>";
                    // save data in database
                    $this->saveAccessToken($request, $pinterestAccessToken, 'pinterest');
                    return view('social-callback', [
                        'link' => $socialLink,
                        'platform' => 'pinterest',
                    ]);
                }
            }
        }
    }
    public function fbCallBack(Request $request){

        $fb = new \Facebook\Facebook([
            'app_id' => env('FACEBOOK_APP_ID'),
            'app_secret' => env('FACEBOOK_APP_SECRET'),
            'default_graph_version' => 'v2.8',
            'persistent_data_handler' => new \App\Services\LaravelPersistentDataHandlerProvider()
        ]);
        // permissions for login user
        $helper = $fb->getRedirectLoginHelper();
        $permissions = ['email', 'public_profile', 'user_posts', 'manage_pages', 'publish_pages'];
        // make login url with permissions
        $loginUrl = $helper->getLoginUrl(env('APP_URL')  . '/fb-call-back', $permissions);

        if (!is_null($request->get('logout'))){
            $request->session()->forget('fb_access_token');
            // remove from database,session
            $shop = $request->session()->get('shop');
            $shop = $this->shopRepository->findByField('name', $shop)->first();
            $socialMediaData =  $this->socialMediaRepository->findByField('shop_id', $shop->id)->first();
            if(!is_null($socialMediaData) && !is_null($socialMediaData->social)) {
                $socialMediaArray = json_decode($socialMediaData->social, TRUE);
                $socialMediaArray['facebook']['access_token'] = '';
                $this->socialMediaRepository->saveAccessToken($request, json_encode($socialMediaArray));
            }
            return view('social-logout', [
                'loginLink' => $loginUrl,
                'platform' => 'facebook'
            ]);
        } else {
            //$fbAccessToken = $request->session()->get('fb_access_token');
        }
        try {
            if (is_null($request->get('logout'))  && $request->get('logout') !='true'){

                $fbAccessToken = $accessToken = $helper->getAccessToken(env('APP_URL') . '/fb-call-back');
                $request->session()->put('fb_access_token', $accessToken);
            }
            // check if user is logged in and have active sessions
            if (isset($fbAccessToken) && $fbAccessToken !='') {
                session(['FBRLH_state' => $_GET['state']]);
                $oResponse = $fb->get('/me?fields=id,name,email', $fbAccessToken);
                $me = $oResponse->getGraphUser();
                $socialLink = $me->getName();
                //$logOutUrl = $helper->getLogoutUrl($fbAccessToken, env('APP_URL')  . '/fb-call-back?logout=true');
                $logOutUrl = env('APP_URL')  . '/fb-call-back?logout=true';
                $socialLink .= " <span><a class='social-logout-pop' href='" . $logOutUrl . "'> Logout</span>";
                // save data in database
                $this->saveAccessToken($request, $fbAccessToken, 'facebook');
            } else {
                $socialLink = $loginUrl;
            }
        } catch (\Facebook\Exceptions\FacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (\Facebook\Exceptions\FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }
        return view('social-callback', [
            'link' => $socialLink,
            'platform' => 'facebook',
        ]);
    }

    public function fbPages(Request $request)
    {

//        $fbAccessToken = $request->session()->get('fb_access_token');
//        $fb = new \Facebook\Facebook([
//            'app_id' => env('FACEBOOK_APP_ID'),
//            'app_secret' => env('FACEBOOK_APP_SECRET'),
//            'default_graph_version' => 'v2.8',
//            'persistent_data_handler' => new \App\Services\LaravelPersistentDataHandlerProvider()
//        ]);
//        $response = $fb->get(
//            '/me/accounts',
//            $fbAccessToken
//        );
//        $pagesEdge = $response->getGraphEdge()->asArray();
//
//        foreach ($pagesEdge as $page) {
//
//            $response = $fb->post(
//                '/'.$page['id'].'/feed',
//                array (
//                    'message' => 'This is a test message -----------'.$page['name'],
//                ),
//                $page['access_token']
//            );
//        }

        $fbAccessToken = $request->session()->get('fb_access_token');
        $pages = array();
        $status = 'error';
        $fb = new \Facebook\Facebook([
            'app_id' => env('FACEBOOK_APP_ID'),
            'app_secret' => env('FACEBOOK_APP_SECRET'),
            'default_graph_version' => 'v2.8',
            'persistent_data_handler' => new \App\Services\LaravelPersistentDataHandlerProvider()
        ]);
        if (isset($fbAccessToken) && $fbAccessToken !='') {
            // get all pages of login user.
            $response = $fb->get(
                '/me/accounts',
                $fbAccessToken
            );
            $pagesEdge = $response->getGraphEdge()->asArray();

            foreach ($pagesEdge as $val => $page) {
               // $pages[$page['access_token']] = $page['name'];
                $pages[$val]['key'] = $page['id'].'---'.$page['access_token'];
                $pages[$val]['value'] = $page['name'];

            }
            $status  = 'success';
            $pages = json_encode($pages);
        }
        return ['status' => $status, 'pages' => $pages];
    }

    public function twitCallBack(Request $request)
    {

        if (!is_null($request->get('logout'))){
            $request->session()->forget('twitter_access_token');
            // remove from database,session
            $shop = $request->session()->get('shop');
            $shop = $this->shopRepository->findByField('name', $shop)->first();
            $socialMediaData =  $this->socialMediaRepository->findByField('shop_id', $shop->id)->first();
            if(!is_null($socialMediaData) && !is_null($socialMediaData->social)) {
                $socialMediaArray = json_decode($socialMediaData->social, TRUE);
                $socialMediaArray['twitter']['access_token'] = '';
                $this->socialMediaRepository->saveAccessToken($request, json_encode($socialMediaArray));
            }
            $connection = new TwitterOAuth(env('TWITTER_APP_ID'), env('TWITTER_APP_SECRET'));
            $request_token = $connection->oauth('oauth/request_token', array('oauth_callback' => env('APP_URL').'/twit-call-back/'));
            $request->session()->put('oauth_token', $request_token['oauth_token']);
            $request->session()->put('oauth_token_secret', $request_token['oauth_token_secret']);
            $url = $connection->url('oauth/authorize', array('oauth_token' => $request_token['oauth_token']));

            return view('social-logout', [
                'loginLink' => $url,
                'platform' => 'twitter'
            ]);
        }

        $request_token = [];
        $request_token['oauth_token'] = $request->session()->get('oauth_token');
        $request_token['oauth_token_secret'] = $request->session()->get('oauth_token_secret');

        if (isset($_REQUEST['oauth_token']) && $request_token['oauth_token'] !== $_REQUEST['oauth_token']) {
            // Abort! Something is wrong.
        }

        $connection = new TwitterOAuth(env('TWITTER_APP_ID'), env('TWITTER_APP_SECRET'), $request_token['oauth_token'], $request_token['oauth_token_secret']);
        $access_token = $connection->oauth("oauth/access_token", ["oauth_verifier" => $_REQUEST['oauth_verifier']]);
        $socialLink = $access_token['screen_name'];

        $logOutUrl = env('APP_URL')  . '/twit-call-back?logout=true';
        $socialLink .= " <span><a class='social-logout-pop' href='" . $logOutUrl . "'> Logout</span>";

        $request->session()->put('twitter_access_token', $access_token);
        $this->saveAccessToken($request, $access_token, 'twitter');
        return view('social-callback', [
            'link' => $socialLink,
            'platform' => 'twitter'
        ]);
    }

    public function saveAccessToken($request, $token, $platFormName){

        $shop = $request->session()->get('shop');
        $shop = $this->shopRepository->findByField('name', $shop)->first();
        $socialMediaData =  $this->socialMediaRepository->findByField('shop_id', $shop->id)->first();

        if(!is_null($socialMediaData) && !is_null($socialMediaData->social)) {
            $socialMediaArray = json_decode($socialMediaData->social, TRUE);
            $socialMediaArray[$platFormName]['access_token'] =  serialize($token) ;
            $this->socialMediaRepository->saveAccessToken($request, json_encode($socialMediaArray));
        } else
        {
            // save data in database
            if (isset($token)  && $token !='') {
                $jsonData = json_encode(array(
                    "$platFormName" => array(
                        "access_token" => serialize($token)
                    )
                ));
                $this->socialMediaRepository->saveAccessToken($request, $jsonData);
            }
        }
    }
}