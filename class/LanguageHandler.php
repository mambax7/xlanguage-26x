<?php

namespace XoopsModules\Xlanguage;

/**
 * Xlanguage extension module
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
use Xoops\Core\Database\Connection;

/**
 * Class XlanguageXlanguageHandler
 */
class LanguageHandler extends \XoopsPersistableObjectHandler
{
    /**
     * @param null|Connection $db
     */
    public function __construct(Connection $db = null)
    {
        parent::__construct($db, 'xlanguage', Language::class, 'xlanguage_id', 'xlanguage_name');
        $this->loadConfig();
    }

    /**
     * @return array|mixed
     */
    public function loadConfig()
    {
        $xoops = \Xoops::getInstance();
        $this->configPath = \XoopsBaseConfig::get('var-path') . '/configs/';
        $this->configFile = $xoops->registry()->get('XLANGUAGE_CONFIG_FILE');
        $this->configFileExt = '.php';

        return $this->cached_config = $this->loadFileConfig();
    }

    /**
     * @return array|mixed
     */
    public function loadFileConfig()
    {
        $cached_config = $this->readConfig();
        if (empty($cached_config)) {
            $cached_config = $this->createConfig();
        }

        return $cached_config;
    }

    /**
     * @return mixed
     */
    public function readConfig()
    {
        $path_file = $this->configPath . $this->configFile . $this->configFileExt;
        \XoopsLoad::load('XoopsFile');
        $file = \XoopsFile::getHandler('file', $path_file);

        return eval(@$file->read());
    }

    /**
     * @return array
     */
    public function createConfig()
    {
        $cached_config = [];
        foreach ($this->getAllLanguage(false) as $key => $language) {
            $cached_config[$language['xlanguage_name']] = $language;
        }
        $this->writeConfig($cached_config);

        return $cached_config;
    }

    /**
     * @param $data
     *
     * @return bool
     */
    public function writeConfig($data)
    {
        if ($this->createPath($this->configPath)) {
            $path_file = $this->configPath . $this->configFile . $this->configFileExt;
            \XoopsLoad::load('XoopsFile');
            $file = \XoopsFile::getHandler('file', $path_file);

            return $file->write('return ' . var_export($data, true) . ';');
        }
    }

    /**
     * @param              $pathname
     * @param mixed|string $pathout
     *
     * @return bool
     */
    private function createPath($pathname, $pathout = null)
    {
        $xoops = \Xoops::getInstance();
        $pathname = mb_substr($pathname, mb_strlen(\XoopsBaseConfig::get('root-path')));
        $pathname = str_replace(DIRECTORY_SEPARATOR, '/', $pathname);

        $dest = (null === $pathout) ? \XoopsBaseConfig::get('root-path') : $pathout;
        $paths = explode('/', $pathname);

        foreach ($paths as $path) {
            if (!empty($path)) {
                $dest = $dest . '/' . $path;
                if (!is_dir($dest)) {
                    if (!mkdir($dest, 0755) && !is_dir($dest)) {
                        return false;
                    }
                    $this->writeIndex($xoops->path('uploads'), 'index.html', $dest);
                }
            }
        }

        return true;
    }

    /**
     * @param $folder_in
     * @param $source_file
     * @param $folder_out
     *
     * @return bool
     */
    private function writeIndex($folder_in, $source_file, $folder_out)
    {
        if (!is_dir($folder_out)) {
            if (!$this->createPath($folder_out)) {
                return false;
            }
        }

        // Simple copy for a file
        if (is_file($folder_in . '/' . $source_file)) {
            return copy($folder_in . '/' . $source_file, $folder_out . '/' . basename($source_file));
        }

        return false;
    }

    /**
     * @param null $name
     * @return |null
     */
    public function getByName($name = null)
    {
        $xoops = \Xoops::getInstance();
        $name = empty($name) ? $xoops->getConfig('locale') : mb_strtolower($name);

        $file_config = $xoops->registry()->get('XLANGUAGE_CONFIG_FILE');
        if (!\XoopsLoad::fileExists($file_config) || !isset($this->cached_config)) {
            $this->loadConfig();
        }

        if (isset($this->cached_config[$name])) {
            return $this->cached_config[$name];
        }

        return null;
    }

    /**
     * @param bool $asobject
     *
     * @return array
     */
    public function getAllLanguage($asobject = true)
    {
        $criteria = new \CriteriaCompo();
        $criteria->setSort('xlanguage_weight');
        $criteria->setOrder('asc');

        return parent::getAll($criteria, null, $asobject, true);
    }

    /**
     * @return mixed|string
     */
    public function renderlist()
    {
        $xoops = \Xoops::getInstance();
        $xoops->tpl()->assign('theme', \XoopsModules\Xlanguage\Helper::getInstance()->getConfig('theme'));
        $xoops->tpl()->assign('languages', $this->getAllLanguage(false));

        return $xoops->tpl()->fetch('admin:xlanguage/xlanguage_admin_list.tpl');
    }
}
