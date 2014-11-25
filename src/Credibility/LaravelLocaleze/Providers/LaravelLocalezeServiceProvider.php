<?php

namespace Credibility\LaravelLocaleze\Providers;

use Illuminate\Support\ServiceProvider;
use Credibility\LaravelLocaleze\LocalezeRequester;
use Credibility\LaravelLocaleze\Localeze;

class LaravelLocalezeServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = true;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
        $namespace = 'laravel-localeze';
        $path = __DIR__ . '/../../..';
		$this->package('credibility/laravel-localeze', $namespace, $path);
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
        $this->app->bind('localeze', function($app) {
            $requester = new LocalezeRequester($app);
            return new Localeze($requester, $app);
        });
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('localeze');
	}

}
