<?php namespace TeslaMN\SocialApi\Providers\Mailru;

use TeslaMN\SocialApi\Providers\Mailru\Rest\Friends;
use TeslaMN\SocialApi\Providers\Mailru\Rest\UserInfo;

class Api
{
    use Friends;
    use UserInfo;

    protected $config;

    public function __construct()
    {
        $this->config = config('services.mailru');
    }

    private function getRequestUrl($params, $uid)
    {
        $params['app_id'] = $this->config['client_id'];
        $params['sig'] = $this->getSignature($params, $uid);

        return http_build_query($params, null, '&');
    }

    private function getSignature($params = array(), $uid)
    {
        ksort($params);
        $raw = $uid . urldecode(http_build_query($params, null, null)) . $this->config['client_public'];
        return md5($raw);
    }

    private function getData($params = array(), $uid = null)
    {
        $url = $this->getRequestUrl($params, $uid);
        $curl = curl_init($this->config['api_address'] . "?" . $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($curl);
        curl_close($curl);
        return json_decode($data, true);
    }
}