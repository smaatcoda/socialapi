<?php namespace TeslaMN\SocialApi\Providers\Odnoklassniki\Rest;


trait UserInfo
{
    public function userInfoGet($uids = null, $token = null, $fields='FIRST_NAME, LAST_NAME, BIRTHDAY, GENDER, LOCATION, PIC1024X768')
    {
        $params['uids'] = $uids;
        $params['fields'] = $fields;
        $result = $this->getData('users.getInfo', $params)[0];
        $remapped_data = [
            'first_name' => !empty($result['first_name']) ? $result['first_name'] : null,
            'last_name' => !empty($result['last_name']) ? $result['last_name'] : null,
            'avatar' => !empty($result['pic1024x768']) ? $result['pic1024x768'] : null,
            'birthday' => !empty($result['birthday']) ? date('Y-m-d', strtotime($result['birthday'])) : null,
            'gender' => !empty($result['gender']) ? $result['gender'] : null,
            'country_code' => !empty($result['location']['countryCode']) ? $result['location']['countryCode'] : null,
            'city' => !empty($result['location']['city']) ? $result['location']['city'] : null,
        ];
        return $remapped_data;
    }
}