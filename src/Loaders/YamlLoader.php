<?php
/**
 * PHP version 5.6
 *
 * @category Loaders
 * @package  Config
 * @author   Dastur1970 <dastur1970@gmail.com>
 * @license  https://github.com/Dastur1970/Config/blob/master/LICENSE MIT License
 * @link     https://github.com/Dastur1970/Config
 */

namespace Dastur\Config\Loaders;

use Symfony\Component\Yaml\Yaml;

/**
 * The yaml loader class.
 *
 * @category Loaders
 * @package  Config
 * @author   Dastur1970 <dastur1970@gmail.com>
 * @license  https://github.com/Dastur1970/Config/blob/master/LICENSE MIT License
 * @link     https://github.com/Dastur1970/Config/
 */
class YamlLoader implements LoaderInterface
{
    /**
     * Get the items from a specific yaml file.
     *
     * @param string $filePath The path of the file being loaded.
     *
     * @return array The array of items.
     */
    public function getItems($filePath)
    {
        return Yaml::parse(file_get_contents($filePath));
    }
}
