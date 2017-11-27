<?php namespace TeslaMN\SocialApi\Providers\Odnoklassniki\Rest;

trait Friends
{
    public function friendsGet($fid = null, $token = null, $uid = null, $sort_type = null)
    {
        if ($uid) $params['uid'] = $uid;
        if ($fid) $params['fid'] = $fid;
        if ($sort_type) $params['sort_type'] = $sort_type;
        return $this->getData('friends.get', $params);
    }
}