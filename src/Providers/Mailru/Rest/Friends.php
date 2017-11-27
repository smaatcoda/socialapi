<?php namespace TeslaMN\SocialApi\Providers\Mailru\Rest;

trait Friends
{
    public function friendsGet($uid = null, $token = null)
    {
        $params['method'] = 'friends.get';
        if ($token) $params['session_key'] = $token;

        return $this->getData($params, $uid);
    }
}