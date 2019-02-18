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

/**
 * Language
 *
 * @copyright       2010-2014 XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         xlanguage
 * @since           2.6.0
 * @author          Laurent JEN (Aka DuGris)

 */
require_once \XoopsBaseConfig::get('root-path') . '/modules/xlanguage/include/vars.php';

/**
 * Class Language
 */
class Language extends \XoopsObject
{
    public function __construct()
    {
        $this->initVar('xlanguage_id', XOBJ_DTYPE_INT, 0, false, 10);
        $this->initVar('xlanguage_name', XOBJ_DTYPE_TXTBOX, '', false);
        $this->initVar('xlanguage_description', XOBJ_DTYPE_TXTBOX, '', false);
        $this->initVar('xlanguage_code', XOBJ_DTYPE_TXTBOX, '', false);
        $this->initVar('xlanguage_charset', XOBJ_DTYPE_TXTBOX, 'utf-8', false);
        $this->initVar('xlanguage_image', XOBJ_DTYPE_TXTBOX, '_unknown.png', false);
        $this->initVar('xlanguage_weight', XOBJ_DTYPE_INT, 1, false, 10);
    }

    /**
     * @param null   $keys
     * @param string $format
     * @param int    $maxDepth
     *
     * @return array
     */
    public function getValues($keys = null, $format = 's', $maxDepth = 1)
    {
        $ret = parent::getValues();
        $ret['xlanguage_image'] = \XoopsBaseConfig::get('url') . '/media/xoops/images/flags/' . \XoopsModules\Xlanguage\Helper::getInstance()->getConfig('theme') . '/' . $this->getVar('xlanguage_image');

        return $ret;
    }

    public function cleanVarsForDB()
    {
        $system = \System::getInstance();
        foreach (parent::getValues() as $k => $v) {
            if ('dohtml' !== $k) {
                if (XOBJ_DTYPE_STIME == $this->vars[$k]['data_type'] || XOBJ_DTYPE_MTIME == $this->vars[$k]['data_type'] || XOBJ_DTYPE_LTIME == $this->vars[$k]['data_type']) {
                    $value = $system->cleanVars($_POST[$k], 'date', date('Y-m-d'), 'date') + $system->cleanVars($_POST[$k], 'time', date('u'), 'int');
                    $this->setVar($k, isset($_POST[$k]) ? $value : $v);
                } elseif (XOBJ_DTYPE_INT == $this->vars[$k]['data_type']) {
                    $value = $system->cleanVars($_POST, $k, $v, 'int');
                    $this->setVar($k, $value);
                } elseif (XOBJ_DTYPE_ARRAY == $this->vars[$k]['data_type']) {
                    $value = $system->cleanVars($_POST, $k, $v, 'array');
                    $this->setVar($k, $value);
                } else {
                    $value = $system->cleanVars($_POST, $k, $v, 'string');
                    $this->setVar($k, $value);
                }
            }
        }
    }
}
