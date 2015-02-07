<?php namespace Antoineaugusti\LaravelEasyrec\Facades;

use Illuminate\Support\Facades\Facade;

class LaravelEasyrec extends Facade
{
	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor() { return 'laraveleasyrec'; }

}