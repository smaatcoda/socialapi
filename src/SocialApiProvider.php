<?php

namespace TeslaMN\SocialApi;

use Illuminate\Support\ServiceProvider;

class SocialApiProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('socialapi', function () {
            return new SocialApi();
        });
    }

}
