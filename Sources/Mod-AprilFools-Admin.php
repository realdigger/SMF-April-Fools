<?php
/**
 * @package SMF April Fools
 * @file Mod-AprilFools-Admin.php
 * @author digger <digger@mysmf.net> <https://mysmf.net>
 * @copyright (c) 2018-2019, digger
 * @license The MIT License (MIT) https://opensource.org/licenses/MIT
 * @version 1.0
 */

/**
 * Add mod admin area
 * @param $admin_areas
 */
function addAprilFoolsAdminArea(&$admin_areas)
{
    global $txt;
    loadLanguage('AprilFools/AprilFools');

    $admin_areas['config']['areas']['modsettings']['subsections']['april_fools'] = array($txt['april_fools']);
}


/**
 * Add mod admin action
 * @param $subActions
 */
function addAprilFoolsAdminAction(&$subActions)
{
    $subActions['april_fools'] = 'addAprilFoolsAdminSettings';
}


/**
 * Add mod settings area
 * @param bool $return_config
 * @return array
 */
function addAprilFoolsAdminSettings($return_config = false)
{
    global $txt, $scripturl, $context, $language;
    loadLanguage('AprilFools/AprilFools');

    $context['page_title'] = $context['settings_title'] = $txt['april_fools'];
    $context['post_url'] = $scripturl . '?action=admin;area=modsettings;save;sa=april_fools';

    $config_vars = array(
        array('check', 'april_fools_enabled'),
        array('check', 'april_fools_test_mode'),
        '',
        (!empty($language) && ($language == 'russian' || $language == 'russian-utf8')) ? array(
            'check',
            'april_fools_funny_dates'
        ) : array(), // Only for Russian
        array('check', 'april_fools_name_flip'),
        array('check', 'april_fools_name_fl_flip'),
        array(
            'select',
            'april_fools_avatar_flip',
            array(
                0 => $txt['april_fools_avatar_flip_none'],
                1 => $txt['april_fools_avatar_flip_horiz'],
                2 => $txt['april_fools_avatar_flip_vert']
            )
        ),
    );

    if ($return_config) {
        return $config_vars;
    }

    if (isset($_GET['save'])) {
        checkSession();
        saveDBSettings($config_vars);
        redirectexit('action=admin;area=modsettings;sa=april_fools');
    }

    prepareDBSettingContext($config_vars);
}
