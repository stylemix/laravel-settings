<?php

namespace Stylemix\Settings\Facades;

/**
 * @method static mixed get($key, $default = null)
 * @method static boolean has($key)
 * @method static set($key, $value = null)
 * @method static forget($key)
 * @method static forgetAll()
 * @method static array all()
 * @method static save()
 * @method static load($force = false)
 */
class Settings extends \Illuminate\Support\Facades\Facade
{
	protected static function getFacadeAccessor()
	{
		return \Stylemix\Settings\SettingsManager::class;
	}
}
