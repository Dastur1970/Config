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

/**
 * The interface for each loader class.
 *
 * @category Loaders
 * @package  Config
 * @author   Dastur1970 <dastur1970@gmail.com>
 * @license  https://github.com/Dastur1970/Config/blob/master/LICENSE MIT License
 * @link     https://github.com/Dastur1970/Config/
 */
interface LoaderInterface
{
    /**
     * Get the items from a specific file.
     *
     * @param string $filePath The path of the file being loaded.
     *
     * @return array The array of items.
     */
    public function getItems($filePath);
}
