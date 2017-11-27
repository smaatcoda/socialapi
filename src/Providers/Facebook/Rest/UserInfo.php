<?php namespace TeslaMN\SocialApi\Providers\Facebook\Rest;

use Yangqi\Htmldom\Htmldom;

trait UserInfo
{
    public function userInfoGet($uid = null, $token = null, $fields = 'address,location,first_name,last_name,birthday,email,gender')
    {

        $user = $this->getData($token, $fields);
        if (!empty($user->getLocation())) {
            $location_array = explode(',', str_replace(' ', '', $user->getLocation()->getName()));
            if (count($location_array) < 2) {
                $city = $location_array[0];
            }else if (count($location_array) == 2) {
                $city = $location_array[0];
                $country =  $location_array[1];
                $country_code = $this->countryCodeGet($country);
            }
        }
        if (!empty($user->getBirthday())){
            $birthday_date = $user->getBirthday()->getTimestamp();
            $birthday = gmdate('Y-m-d', $birthday_date);
        }
        $remapped_data = [
            'first_name' => !empty($user->getFirstName()) ? $user->getFirstName() : null,
            'last_name' => !empty($user->getLastName()) ? $user->getLastName() : null,
            'avatar' => 'http://graph.facebook.com/' . $uid . '/picture?type=large',
            'birthday' => !empty($birthday) ? $birthday : null,
            'gender' => !empty($user->getGender()) ? $user->getGender() : null,
            'country_code' => !empty($country_code) ? $country_code : null,
            'city' => !empty($city) ? $city : null,
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