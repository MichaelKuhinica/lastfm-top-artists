<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Helpers\LastFmClient;
use GuzzleHttp\Client;

class LastFmServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
      $this->app->singleton('App\Helpers\Contracts\LastFmClientContract', function(){
        $config = isset($this->app['config']['app']['lastfm']) ? $this->app['config']['app']['lastfm'] : [];
        return new LastFmClient($config, new \GuzzleHttp\Client($config));
      });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['App\Helpers\Contracts\LastFmClientContract'];
    }
}
