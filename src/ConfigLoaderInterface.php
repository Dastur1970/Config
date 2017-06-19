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
 * The config loader class interface.
 *
 * @category Config
 * @package  Config
 * @author   Dastur1970 <dastur1970@gmail.com>
 * @license  https://github.com/Dastur1970/Config/blob/master/LICENSE MIT License
 * @link     https://github.com/Dastur1970/Config/
 */
interface ConfigLoaderInterface
{
    /**
     * Load all of the config items.
     *
     * @return array The config items.
     */
    public function load();
}
