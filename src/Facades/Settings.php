<?php

namespace Stylemix\Settings\Facades;

/**
 * @method mixed get($key, $default = null)
 * @method boolean has($key)
 * @method set($key, $value = null)
 * @method forget($key)
 * @method forgetAll()
 * @method array all()
 * @method save()
 * @method load($force = false)
 */
class Settings extends \Illuminate\Support\Facades\Facade
{
	protected static function getFacadeAccessor()
	{
		return \Stylemix\Settings\SettingsManager::class;
	}
}
