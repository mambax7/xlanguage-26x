<?php
/**
 * Xlanguage extension module
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       2010-2014 XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         xlanguage
 * @since           2.6.0
 * @author          Laurent JEN (Aka DuGris)
 */
use Xoops\Core\PreloadItem;

/**
 * Xlanguage core preloads
 *
 * @package   Xlanguage
 * @author    trabis <lusopoemas@gmail.com>
 * @copyright 2010-2014 XOOPS Project (https://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 */
class XlanguagePreload extends PreloadItem
{
    /**
     * @param array $args
     */
    public static function eventCoreIncludeCommonEnd($args)
    {
        require_once __DIR__ . '/autoloader.php';

        if (\XoopsLoad::fileExists($hnd_file = dirname(__DIR__) . '/api.php')) {
            require_once $hnd_file;
        }
    }

    /**
     * @param array $args
     */
    public static function eventCoreHeaderCheckcache($args)
    {
        $xoops = \Xoops::getInstance();
        xlanguage_select_show(explode('|', $xoops->registry()->get('XLANGUAGE_THEME_OPTIONS')));
    }
}
