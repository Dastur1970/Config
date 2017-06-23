<?php
/**
 * PHP version 5.6
 *
 * @category Config
 * @package  Config
 * @author   Dastur1970 <dastur1970@gmail.com>
 * @license  https://github.com/Dastur1970/Config/blob/master/LICENSE MIT License
 * @link     https://github.com/Dastur1970/Config
 */

namespace Dastur\Config;

/**
 * The configuration class.
 *
 * @category Config
 * @package  Config
 * @author   Dastur1970 <dastur1970@gmail.com>
 * @license  https://github.com/Dastur1970/Config/blob/master/LICENSE MIT License
 * @link     https://github.com/Dastur1970/Config/
 */
class Config implements ConfigInterface
{

    /**
     * The delimiter used when exploding a class.
     *
     * Value is '.'
     */
    const DELIMITER = '.';

    /**
     * The config values.
     *
     * @var array
     */
    protected $values = [];

    /**
     * Create a new config instance.
     *
     * @param Dastur\Config\ConfigLoaderInterface $loader The config loader.
     */
    public function __construct(ConfigLoaderInterface $loader)
    {
        $this->values = $loader->load();
    }

    /**
     * Get a config item.
     *
     * @param string $path    The path of the config item.
     * @param mixed  $default The default value if nothing is found.
     *
     * @return mixed The config item or default value.
     */
    public function get($path, $default = null)
    {
        $parts = $this->getParts($path);
        $value = $this->values;
        foreach ($parts as $part) {
            if (! isset($value[$part])) {
                return $defaut;
            }
            $value = $value[$part];
        }
        return $value;
    }

    /**
     * Set a config value. This does not change the value in
     * The configuration file, just the runtime configuration array.
     *
     * @param string $path  The path of the config item being set.
     * @param mixed  $value What you are setting the config item to.
     *
     * @return void
     */
    public function set($path, $value)
    {
        $parts = $this->getParts($path);
        $values = $this->values;
        while (count($parts) > 1) {
            $part = array_shift($parts);
            if (! isset($values[$part])) {
                $values[$part] = [];
            }
            $values = $values[$part];
        }
        $values[array_shift($parts)] = $value;
    }

    /**
     * Check if a config item has been set.
     *
     * @param string $path The path of the config item being checked.
     *
     * @return boolean Whether or not a config item has been set.
     */
    public function has($path)
    {
        $parts = $this->getParts($path);
        $values = $this->values;
        while (! empty($parts)) {
            $part = array_shift($parts);
            if (! isset($values[$part])) {
                return false;
            }
            $values = $values[$part];
        }
        return true;
    }

    /**
     * Get all of the config items that are set.
     *
     * @return array All of the config items.
     */
    public function all()
    {
        return $this->values;
    }

    /**
     * Get the parts of the path.
     *
     * @param string $path The path getting exploded.
     *
     * @return array
     */
    protected function getParts($path)
    {
        return explode(static::DELIMITER, $path);
    }
}
