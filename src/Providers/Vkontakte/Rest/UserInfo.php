<?php namespace TeslaMN\SocialApi\Providers\Vkontakte\Rest;

use Yangqi\Htmldom\Htmldom;

trait UserInfo
{
    public function userInfoGet($user_ids = null, $token = null, $fields = 'first_name, last_name, bdate, sex, country, photo_max_orig, city', $lang = 'ru')
    {
        $params['user_ids'] = $user_ids;
        $params['lang'] = $lang;
        $params['fields'] = $fields;
        $result = $this->getData('users.get', $params)[0];
        $genders = ['', 'female', 'male'];
        $remapped_data = [
            'first_name' => !empty($result['first_name']) ? $result['first_name'] : null,
            'last_name' => !empty($result['last_name']) ? $result['last_name'] : null,
            'avatar' => !empty($result['photo_max_orig']) ? $result['photo_max_orig'] : null,
            'birthday' => !empty($result['bdate']) ? date('Y-m-d', strtotime($result['bdate'])) : null,
            'gender' => (isset($result['sex']) && !empty($genders[$result['sex']])) ? $genders[$result['sex']] : null,
            'country_code' => !empty($result['country']) ? $this->countryCodeGet($this->countryInfoGet($result['country'], 'en')) : null,
            'city' => !empty($result['city']) ? $this->cityInfoGet($result['city']) : null,
        ];
        return $remapped_data;
    }

    public function cityInfoGet($city_ids, $lang = 'ru')
    {
        $params['city_ids'] = $city_ids;
        $params['lang'] = $lang;
        $result = $this->getData('database.getCitiesById', $params)[0];
        return $result['name'];
    }

    public function countryInfoGet($country_ids, $lang = 'ru')
    {
        $params['country_ids'] = $country_ids;
        $params['lang'] = $lang;
        $result = $this->getData('database.getCountriesById', $params)[0];
        return $result['name'];
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