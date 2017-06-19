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

use Dastur\Config\Exceptions\ConfigLoadingException;

use Dastur\Config\Loaders\JsonLoader;
use Dastur\Config\Loaders\PhpLoader;
use Dastur\Config\Loaders\XmlLoader;
use Dastur\Config\Loaders\YamlLoader;
use Dastur\Config\Loaders\IniLoader;

/**
 * The class that loads all of the configurations.
 *
 * @category Config
 * @package  Config
 * @author   Dastur1970 <dastur1970@gmail.com>
 * @license  https://github.com/Dastur1970/Config/blob/master/LICENSE MIT License
 * @link     https://github.com/Dastur1970/Config/
 */
class ConfigLoader
{
    /**
     * All of the config files
     *
     * @var array
     */
    protected $files = [];

    /**
     * Whether or not it is being loaded from cache.
     *
     * @var boolean
     */
    protected $usesCache;

    /**
     * The cache file directory.
     *
     * @var string
     */
    protected $cachePath;

    /**
     * The json file loader.
     *
     * @var Dastur\Config\Loaders\JsonLoader
     */
    protected $json;

    /**
     * The php file loader.
     *
     * @var Dastur\Config\Loaders\PhpLoader
     */
    protected $php;

    /**
     * The xml file loader.
     *
     * @var Dastur\Config\Loaders\XmlLoader
     */
    protected $xml;

    /**
     * The yaml file loader.
     *
     * @var Dastur\Config\Loaders\YamlLoader
     */
    protected $yaml;

    /**
     * The ini file loader.
     *
     * @var Dastur\Config\Loaders\IniLoader
     */
    protected $ini;

    /**
     * The supported file types.
     *
     * @var array
     */
    protected $fileTypes = [
        'json',
        'php',
        'xml',
        'yaml',
        'ini'
    ];

    /**
     * Create a new ConfigLoader instance.
     *
     * @param mixed                            $files      Array of files or
     *                                                     directories that
     *                                                     contain config files.
     * @param string                           $cachePath  The directory that
     *                                                     the config cache
     *                                                     is stored in.
     * @param boolean                          $usesCache  Whethe or not
     *                                                     caching is turned on.
     * @param Dastur\Config\Loaders\JsonLoader $jsonLoader The json loader.
     * @param Dastur\Config\Loaders\PhpLoader  $phpLoader  The php loader.
     * @param Dastur\Config\Loaders\XmlLoader  $xmlLoader  The xml loader.
     * @param Dastur\Config\Loaders\YamlLoader $yamlLoader The yaml loader.
     * @param Dastur\Config\Loaders\IniLoader  $iniLoader  The ini loader.
     */
    public function __construct(
        $files,
        $cachePath,
        $usesCache,
        JsonLoader $jsonLoader,
        PhpLoader $phpLoader,
        XmlLoader $xmlLoader,
        YamlLoader $yamlLoader,
        IniLoader $iniLoader
    ) {
        $this->cachePath = substr($cachePath, -1) === '/'
            ? $cachePath : $cachePath . '/';
        $this->usesCache = $usesCache;
        $this->json = $jsonLoader;
        $this->phpLoader = $phpLoader;
        if (! ($usesCache && $this->cachedFileExists())) {
            $this->files = $this->getFiles((array) $files);
        }
    }

    /**
     * Load all of the config items.
     *
     * @return array The config items.
     *
     * @throws Dastur\Config\Exceptions\ConfigLoaderException
     */
    public function load()
    {
        if ($this->cachedFileExists() && $this->usesCache) {
            return $this->loadConfigFromCache();
        }
        $values = $this->loadConfigFromFiles();
        if (! $this->cachedFileExists() && $this->usesCache) {
            $this->cacheConfig($values);
        }
        return $values;
    }

    /**
     * Get an array of all of the files.
     *
     * @param array $fOrDs All of the files and directories.
     *
     * @return array An array of all the files.
     *
     * @throws Dastur\Config\Exceptions\ConfigLoadingException
     */
    protected function getFiles(array $fOrDs)
    {
        $files = [];
        foreach ($fOrDs as $fOrD) {
            if (is_file($fOrD)) {
                $files[] = $fOrD;
                continue;
            }
            if (is_dir($fOrD)) {
                $files = array_merge(
                    $files,
                    $this->getFiles($this->scanDir($fOrD))
                );
                continue;
            }
            throw new ConfigLoadingException(
                'The path ' . $fOrD . ' is neither a directory nor a file.'
            );
        }
        return $files;
    }

    /**
     * Scan a directory and return full full file paths.
     *
     * @param string $directory The directory path.
     *
     * @return array
     */
    protected function scanDir($directory)
    {
        $values = [];
        $files = scandir($directory);
        $dir = substr($directory, -1) === '/' ? $directory : $directory . '/';
        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }
            $values[] = $dir . $file;
        }
        return $values;
    }

    /**
     * Get the path for the cached config file.
     *
     * @return string The file path.
     */
    protected function getCachedFilePath()
    {
        return $this->cachePath . 'config.json';
    }

    /**
     * Determine if the cache file exists.
     *
     * @return boolean Whether or not it exists.
     */
    protected function cachedFileExists()
    {
        return is_file($this->getCachedFilePath());
    }

    /**
     * Load the config from the cache.
     *
     * @return array
     */
    protected function loadConfigFromCache()
    {
        return json_decode(file_get_contents($this->getCachedFilePath()), true);
    }

    /**
     * Set the config into the cache.
     *
     * @param array $values The config array.
     *
     * @return void
     */
    protected function cacheConfig(array $values)
    {
        file_put_contents($this->getCachedFilePath(), json_encode($values));
    }

    /**
     * Load the config from the files.
     *
     * @return array
     *
     * @throws Dastur\Config\Exceptions\ConfigLoaderException
     */
    protected function loadConfigFromFiles()
    {
        $values = [];
        foreach ($this->files as $file) {
            $extension = pathinfo($file, PATHINFO_EXTENSION);
            $name = pathinfo($file, PATHINFO_FILENAME);
            if (! $this->isSupportedFileType($extension)) {
                throw new ConfigLoaderException(
                    'Can not load file' . $file . ' because '
                    . 'the file type is not supported'
                );
            }
            $values[$name] = $this->{$extension}->getItems();
        }
        return $values;
    }

    /**
     * Determine whether the given extension is supported.
     *
     * @param string $extension The extension being checked.
     *
     * @return boolean Whether or not it's supported.
     */
    protected function isSupportedFileType($extension)
    {
        return in_array($extension, $this->fileTypes);
    }
}
