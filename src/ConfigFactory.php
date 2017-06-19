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

use Dastur\Config\ConfigLoader;

use Dastur\Config\Loaders\JsonLoader;
use Dastur\Config\Loaders\PhpLoader;
use Dastur\Config\Loaders\XmlLoader;
use Dastur\Config\Loaders\YamlLoader;
use Dastur\Config\Loaders\IniLoader;

/**
 * A factory for the config class.
 *
 * @category Config
 * @package  Config
 * @author   Dastur1970 <dastur1970@gmail.com>
 * @license  https://github.com/Dastur1970/Config/blob/master/LICENSE MIT License
 * @link     https://github.com/Dastur1970/Config/
 */
class ConfigFactory
{
    /**
     * Make a new Config instance.
     *
     * @param mixed   $files     The files being loaded.
     * @param string  $cachePath The cache path.
     * @param boolean $usesCache Whether or not you are using the cache.
     *
     * @return Dastur\Config\Config A config instance.
     */
    public static function make($files, $cachePath = null, $usesCache = false)
    {
        return new Config(
            ConfigLoaderFactory::make($files, $cachePath, $usesCache)
        );
    }
}
