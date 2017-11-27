<?php namespace TeslaMN\SocialApi\Providers\Mailru\Rest;

use Yangqi\Htmldom\Htmldom;

trait UserInfo
{
    public function userInfoGet($uid = null, $token = null)
    {
        $params['method'] = 'users.getInfo';
        if ($token) $params['session_key'] = $token;
        $result = $this->getData($params, $uid)[0];
        $genders = ['male', 'female'];
        $remapped_data = [
            'first_name' => !empty($result['first_name']) ? $result['first_name'] : null,
            'last_name' => !empty($result['last_name']) ? $result['last_name'] : null,
            'avatar' => !empty($result['pic_big']) ? $result['pic_big'] : null,
            'birthday' => !empty($result['birthday']) ? date('Y-m-d', strtotime($result['birthday'])) : null,
            'gender' => (isset($result['sex']) && !empty($genders[$result['sex']])) ? $genders[$result['sex']] : null,
            'country_code' => !empty($result['location']['country']['name']) ? $this->countryCodeGet($result['location']['country']['name']) : null,
            'city' => !empty($result['location']['city']['name']) ? $result['location']['city']['name'] : null,
        ];
        return $remapped_data;
    }


    public function countryCodeGet($country_name)
    {

        $url = 'https://vk.com/dev/country_codes';
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Cookie: remixlang=3\r\n', 'header: Accept-language: en\r\n'));
        $file = curl_exec($curl);
        curl_close($curl);
        $html = new Htmldom($file);
        $countries = $html->find('table.wk_table tr');
        if (count($countries) < 1) return null;

        foreach ($countries as $country) {
            if (count($country->children) !== 2) continue;

            if ($this->getTitle($country) !== $country_name . ' ') continue;
            return str_replace(' ', '', $this->getISO2($country));
        }
        return null;

    }

    public function getISO2($country)
    {
        return $country->children[0]->plaintext;
    }

    public function getTitle($country)
    {
        return $country->children[1]->plaintext;
    }
}