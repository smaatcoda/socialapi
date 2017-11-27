<?php 

use TeslaMN\SocialApi\Facades\SocialApi;

class SocialApiTest extends PHPUnit_Framework_TestCase
{
    /**@test*/
    public function socialapi_works()
    {
        $vk_user = SocialApi::provider('vkontakte')->userInfoGet('ares_95');
        self::assertTrue(count($vk_user) > 0);
    }
}