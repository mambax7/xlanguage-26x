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

 *
 * @param $options
 *
 * @return array
 */
function b_xlanguage_select_show($options)
{
    $xoops = \Xoops::getInstance();
    $helper = \XoopsModules\Xlanguage\Helper::getInstance();

    $xlanguage = $xoops->registry()->get('XLANGUAGE');
    $lang_tag = $xoops->registry()->get('XLANGUAGE_LANG_TAG');

    $options[3] = $helper->getConfig('theme');

    $block = [];
    $helper->getHandler('Language')->loadConfig();

    if (!is_array($helper->getHandler('Language')->cached_config) || count($helper->getHandler('Language')->cached_config) < 1) {
        return $block;
    }

    $QUERY_STRING_array = array_filter(explode('&', $xoops->getEnv('QUERY_STRING')));
    $QUERY_STRING_new = [];
    foreach ($QUERY_STRING_array as $QUERY) {
        if (mb_substr($QUERY, 0, (mb_strlen($lang_tag) + 1)) != $lang_tag . '=') {
            $vals = explode('=', $QUERY);
            foreach (array_keys($vals) as $key) {
                if (preg_match('/^a-z0-9$/i', $vals[$key])) {
                    $vals[$key] = urlencode($vals[$key]);
                }
            }
            $QUERY_STRING_new[] = implode('=', $vals);
        }
    }

    $block['display'] = $options[0];
    $block['delimitor'] = $options[1];
    $block['number'] = $options[2];

    if ('jquery' === $options[0]) {
        $xoops = \Xoops::getInstance();
        $xoops->theme()->addBaseScriptAssets('@jqueryui');
    }

    $block['selected'] = $xlanguage['lang'];

    if ('images' === $options[0] || 'text' === $options[0]) {
        $query_string = htmlspecialchars(implode('&', $QUERY_STRING_new));
        $query_string .= empty($query_string) ? '' : '&amp;';
    } else {
        $query_string = implode('&', array_map('htmlspecialchars', $QUERY_STRING_new));
        $query_string .= empty($query_string) ? '' : '&';
    }
    $block['url'] = $xoops->getEnv('PHP_SELF') . '?' . $query_string . $lang_tag . '=';
    $block['languages'] = $helper->getHandler('Language')->cached_config;

    return $block;
}

/**
 * @param $options
 *
 * @return string
 */
function b_xlanguage_select_edit($options)
{
    $block_form = new \Xoops\Form\BlockForm();

    $tmp = new \Xoops\Form\Select(_MB_XLANGUAGE_DISPLAY_METHOD . ' : ', 'options[0]', $options[0]);
    $tmp->addOption('images', _MB_XLANGUAGE_DISPLAY_FLAGLIST);
    $tmp->addOption('text', _MB_XLANGUAGE_DISPLAY_TEXTLIST);
    $tmp->addOption('select', _MB_XLANGUAGE_DISPLAY_SELECT);
    $tmp->addOption('jquery', _MB_XLANGUAGE_DISPLAY_JQUERY);
    $tmp->addOption('bootstrap', _MB_XLANGUAGE_DISPLAY_BOOTSTRAP);
    $block_form->addElement($tmp);

    $block_form->addElement(new \Xoops\Form\Text(_MB_XLANGUAGE_IMAGE_SEPARATOR . ' (' . _MB_XLANGUAGE_OPTIONAL . ') : ', 'options[1]', 5, 5, $options[1]));
    $block_form->addElement(new \Xoops\Form\Text(_MB_XLANGUAGE_IMAGE_PERROW . ' (' . _MB_XLANGUAGE_OPTIONAL . ') : ', 'options[2]', 2, 2, $options[2]));

    return $block_form->render();
}
