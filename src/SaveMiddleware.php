<?php

namespace Stylemix\Settings;

use Closure;
use Illuminate\Contracts\Routing\TerminableMiddleware;

// https://github.com/anlutro/laravel-settings/issues/43
if (interface_exists('Illuminate\Contracts\Routing\TerminableMiddleware')) {
	interface LaravelIsStupidMiddleware extends TerminableMiddleware {}
} else {
	interface LaravelIsStupidMiddleware {}
}

class SaveMiddleware implements LaravelIsStupidMiddleware
{
	/**
	 * Create a new save settings middleware
	 * 
	 * @param SettingStore $settings
	 */
	public function __construct(SettingStore $settings)
	{
		$this->settings = $settings;
	}

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		$response = $next($request);
		
		return $response;
	}

	/**
	 * Perform any final actions for the request lifecycle.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Symfony\Component\HttpFoundation\Response  $response
	 * @return void
	 */
	public function terminate($request, $response)
	{
		$this->settings->save();
	}
}