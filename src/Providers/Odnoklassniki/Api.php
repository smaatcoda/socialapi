<?php namespace TeslaMN\SocialApi\Providers\Odnoklassniki;

use TeslaMN\SocialApi\Providers\Odnoklassniki\Rest\Friends;
use TeslaMN\SocialApi\Providers\Odnoklassniki\Rest\UserInfo;

class Api
{
    use Friends;
    use UserInfo;

    protected $config;

    public function __construct()
    {
        $this->config = config('services.odnoklassniki');
    }

    private function getRequestUrl($method, $params)
    {
        $params['method'] = $method;
        $params['application_key'] = $this->config['client_public'];
        $params['sig'] = $this->getSignature($params);
        $params['access_token'] = $this->config['access_token'];
        return http_build_query($params, null, '&');
    }

    private function getSignature($params = array())
    {
        ksort($params);
        $request = urldecode(http_build_query($params, null, null));
        $request .= md5($this->config['access_token'] . $this->config['client_secret']);
        return md5($request);
    }

    private function getData($method, $params = array())
    {
        $url = $this->getRequestUrl($method, $params);
        $curl = curl_init($this->config['api_address'] . "?" . $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($curl);
        curl_close($curl);
        return json_decode($data, true);
    }
}