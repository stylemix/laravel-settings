<?php

namespace Stylemix\Settings;

class Facade extends \Illuminate\Support\Facades\Facade
{
	protected static function getFacadeAccessor()
	{
		return 'Stylemix\Settings\SettingsManager';
	}
}
