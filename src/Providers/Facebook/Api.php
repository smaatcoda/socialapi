<?php namespace TeslaMN\SocialApi\Providers\Facebook;

use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use TeslaMN\SocialApi\Providers\Facebook\Rest\Friends;
use TeslaMN\SocialApi\Providers\Facebook\Rest\UserInfo;

class Api
{
    use UserInfo;
    use Friends;

    protected $config;

    public function __construct()
    {
        $this->config = config('services.facebook');
    }

    private function getData($token, $fields)
    {
        $fb = new Facebook([
            'app_id' => $this->config['client_id'],
            'app_secret' => $this->config['client_secret'],
            'default_graph_version' => 'v2.5'
        ]);
        try {
            // Returns a `Facebook\FacebookResponse` object
            $response = $fb->get('/me?fields=' . $fields, $token);
        } catch (FacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }
        return $response->getGraphUser();
    }
}