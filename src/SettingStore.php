<?php

namespace Stylemix\Settings;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\BufferedOutput;

abstract class SettingStore
{

    /**
     * The settings data.
     *
     * @var array
     */
    protected $data = array();

    /**
     * Whether the store has changed since it was last loaded.
     *
     * @var boolean
     */
    protected $unsaved = false;

    /**
     * Whether the settings data are loaded.
     *
     * @var boolean
     */
    protected $loaded = false;

    /**
     * Get a specific key from the settings data.
     *
     * @param  string|array $key
     * @param  mixed        $default Optional default value.
     *
     * @return mixed
     */
    public function get($key, $default = null)
    {
        $this->load();

        return Arr::get($this->data, $key, $default);
    }

    /**
     * Determine if a key exists in the settings data.
     *
     * @param  string $key
     *
     * @return boolean
     */
    public function has($key)
    {
        $this->load();

        return Arr::has($this->data, $key);
    }

    /**
     * Set a specific key to a value in the settings data.
     *
     * @param string|array $key   Key string or associative array of key => value
     * @param mixed        $value Optional only if the first argument is an array
     */
    public function set($key, $value = null)
    {
        $this->load();
        $this->unsaved = true;

        if (is_array($key)) {
            foreach ($key as $k => $v) {
                Arr::set($this->data, $k, $v);
            }
        }
        else {
            Arr::set($this->data, $key, $value);
        }
    }

    /**
     * Unset a key in the settings data.
     *
     * @param  string $key
     */
    public function forget($key)
    {
        $this->unsaved = true;

        if ($this->has($key)) {
            Arr::forget($this->data, $key);
        }
    }

    /**
     * Unset all keys in the settings data.
     *
     * @return void
     */
    public function forgetAll()
    {
        $this->unsaved = true;
        $this->data    = array();
    }

    /**
     * Get all settings data.
     *
     * @return array
     */
    public function all()
    {
        $this->load();

        return $this->data;
    }

    /**
     * Save any changes done to the settings data.
     *
     * @return void
     */
    public function save()
    {
        if (!$this->unsaved) {
            // either nothing has been changed, or data has not been loaded, so
            // do nothing by returning early
            return;
        }

        $this->write($this->data);
        $this->unsaved = false;

		if (!app()->runningUnitTests()) {
			$this->writeToConfig();

			// Force restart queue workers when settings were changed to
			// make new setting values applied, since they are loaded once into memory
			if (Config::get('settings.queue_restart')) {
				try {
					$this->restartQueues();
				}
				catch (\Exception $e) {
					report($e);
				}
			}
		}
	}

	/**
	 * Write mapped to config values to bootstrap script
	 */
	protected function writeToConfig()
	{
		$config_keys = Config::get('settings.config_mapping');
		$cache_file  = app()->bootstrapPath('cache/settings.php');

		if (empty($config_keys)) {
			if (file_exists($cache_file)) {
				@unlink($cache_file);
			}

			return;
		}

		$config_data = [];
		foreach ($config_keys as $key => $config) {
			if (null !== ($value = $this->get($key))) {
				$config_data[$config] = $value;
			}
		}

		$output = "<?php\n\nreturn " . var_export($config_data, true) . ";" . \PHP_EOL;
		@file_put_contents($cache_file, $output);
	}

    /**
     * Make sure data is loaded.
     *
     * @param boolean $force Force a reload of data. Default false.
     */
    public function load($force = false)
    {
        if (!$this->loaded || $force) {
            $this->data   = $this->read();
            $this->loaded = true;
        }
    }

    public function __destruct()
	{
		$this->save();
	}

	/**
     * Read the data from the store.
     *
     * @return array
     */
    abstract protected function read();

    /**
     * Write the data into the store.
     *
     * @param  array $data
     *
     * @return void
     */
    abstract protected function write(array $data);

	/**
	 * Restart queues workers (Horizon, Simple queue)
	 */
	public function restartQueues()
	{
		if (class_exists('Laravel\Horizon\Console\TerminateCommand')) {
			// In some environments SIGTERM is not defined in PHP
			defined('SIGTERM') || define('SIGTERM', 15);
			$this->runConsoleCommand(\Laravel\Horizon\Console\TerminateCommand::class);
		}

		if (class_exists('Illuminate\Queue\Console\RestartCommand')) {
			$this->runConsoleCommand(\Illuminate\Queue\Console\RestartCommand::class);
		}
	}

	protected function runConsoleCommand($command)
	{
		$output = new BufferedOutput();
		$cmd = app($command);
		$cmd->setLaravel(app());
		$code = $cmd->run(new StringInput(''), $output);

		if ($code !== 0) {
			throw new \RuntimeException($output->fetch());
		}
	}
}
