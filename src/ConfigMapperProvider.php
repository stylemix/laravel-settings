<?php

namespace Stylemix\Settings;

class ConfigMapperProvider extends \Illuminate\Support\ServiceProvider
{

	public function register()
	{
		if (!$this->app->bound('config')) {
			return;
		}

		$cache_file = $this->app->bootstrapPath('cache/settings.php');

		if (!file_exists($cache_file)) {
			return;
		}

		$config_data = include $cache_file;

		foreach ($config_data as $path => $value) {
			$this->app['config']->set($path, $value);
		}
	}
}
