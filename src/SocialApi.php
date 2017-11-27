<?php

namespace TeslaMN\SocialApi;

class SocialApi
{
    protected $parentClass;
    protected $providers = ['odnoklassniki', 'vkontakte', 'mailru', 'facebook'];
    protected $provider;

    public function provider($provider)
    {
        if (!in_array($provider, $this->providers)) return false;
        $parentClass = '\TeslaMN\SocialApi\Providers\\' . ucfirst($provider) . '\Api';
        $this->parentClass = new $parentClass;
        $this->provider = $provider;
        return $this;
    }

    public function __call($name, $arguments)
    {
        if (!in_array($this->provider, $this->providers)) return false;
        return call_user_func_array([$this->parentClass, $name], $arguments);
    }

}