<?php namespace TeslaMN\SocialApi\Providers\Vkontakte\Rest;

trait Friends
{
    public function friendsGet($user_id = null, $token = null, $fields = null, $order = null, $list_id = null)
    {
        if ($user_id) $params['user_id'] = $user_id;
        if ($order) $params['order'] = $order;
        if ($list_id) $params['list_id'] = $list_id;
        if ($fields) $params['fields'] = $fields;
        return $this->getData('friends.get', $params);
    }
}