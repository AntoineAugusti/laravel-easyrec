<?php namespace Antoineaugusti\LaravelEasyrec;

use Illuminate\Support\ServiceProvider;

class LaravelEasyrecServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('antoineaugusti/laravel-easyrec', 'antoineaugusti/laravel-easyrec');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app['laraveleasyrec'] = $this->app->share(function($app) {
			$config = [];

			foreach (['baseURL', 'apiKey', 'tenantID', 'apiVersion'] as $value)
				$config[$value] = $app['config']->get('antoineaugusti/laravel-easyrec::'.$value);

			return new Easyrec($config);
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('laraveleasyrec');
	}

}
