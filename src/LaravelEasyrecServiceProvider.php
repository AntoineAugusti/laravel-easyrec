<?php namespace Antoineaugusti\LaravelEasyrec;

use Illuminate\Support\ServiceProvider;

class LaravelEasyrecServiceProvider extends ServiceProvider {

	/**
	 * Boot the service provider.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->setupConfig();
	}

	/**
	 * Setup the config.
	 *
	 * @return void
	 */
	protected function setupConfig()
	{
		$source = realpath(__DIR__.'/../config/easyrec.php');
		$this->publishes([$source => config_path('easyrec.php')]);
		$this->mergeConfigFrom($source, 'easyrec');
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
				$config[$value] = $this->app->config->get('easyrec.'.$value);

			return new Easyrec($config);
		});
	}
}
