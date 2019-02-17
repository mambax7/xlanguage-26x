<?php

namespace XoopsModules\Xlanguage;

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright       2010-2014 XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         xlanguage
 * @since           2.6.0
 * @author          Laurent JEN (Aka DuGris)
 * @version         $Id$
 */
class Helper extends \Xoops\Module\Helper\HelperAbstract
{
    /**
     * Init the module
     *
     * @return null|void
     */
    public function init()
    {
        if (\XoopsLoad::fileExists($hnd_file = \XoopsBaseConfig::get('root-path') . '/modules/xlanguage/include/vars.php')) {
            require_once $hnd_file;
        }

        if (\XoopsLoad::fileExists($hnd_file = \XoopsBaseConfig::get('root-path') . '/modules/xlanguage/include/functions.php')) {
            require_once $hnd_file;
        }
        $this->setDirname('xlanguage');
    }

    /**
     * @return \Xoops\Module\Helper\HelperAbstract
     */
    public static function getInstance()
    {
        return parent::getInstance();
    }

    /**
     * @return LanguageHandler
     */
    public function getHandlerLanguage()
    {
        return $this->getHandler('Language');
    }

    /**
     * Get an Object Handler
     *
     * @param string $name name of handler to load
     *
     * @return bool|\XoopsObjectHandler|\XoopsPersistableObjectHandler
     */
    public function getHandler($name)
    {
        $ret = false;
        //        /** @var Connection $db */
        $db = \XoopsDatabaseFactory::getConnection();
        $class = '\\XoopsModules\\' . ucfirst(mb_strtolower(basename(dirname(__DIR__)))) . '\\' . $name . 'Handler';
        $ret = new $class($db);

        return $ret;
    }
}
