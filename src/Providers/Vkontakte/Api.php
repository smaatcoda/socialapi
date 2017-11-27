<?php namespace TeslaMN\SocialApi\Providers\Vkontakte;

use TeslaMN\SocialApi\Providers\Vkontakte\Rest\Friends;
use TeslaMN\SocialApi\Providers\Vkontakte\Rest\UserInfo;

class Api
{
    use Friends;
    use UserInfo;

    protected $config;

    public function __construct()
    {
        $this->config = config('services.vkontakte');
    }

    private function getRequestUrl($params)
    {
        $params['access_token'] = $this->config['access_token'];
        return http_build_query($params, null, '&');
    }

    private function getData($method, $params = array())
    {
        $url = $this->getRequestUrl($params);
        $response = file_get_contents($this->config['api_address'] . $method . "?" . $url);
        $data = json_decode($response, true);
        return $data['response'];
    }
}