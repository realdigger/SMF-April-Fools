<?php
/**
 * @package SMF April Fools
 * @file Mod-AprilFools.php
 * @author digger <digger@mysmf.net> <http://mysmf.net>
 * @copyright Copyright (c) 2018, digger
 * @license The MIT License (MIT) https://opensource.org/licenses/MIT
 * @version 1.0
 */

if (!defined('SMF')) {
    die('Hacking attempt...');
}


/**
 * Load all needed hooks
 */
function loadAprilFoolsHooks()
{
    global $modSettings;

    add_integration_function('integrate_admin_include', '$sourcedir/Mod-AprilFools-Admin.php', false);
    add_integration_function('integrate_admin_areas', 'addAprilFoolsAdminArea', false);
    add_integration_function('integrate_modify_modifications', 'addAprilFoolsAdminAction', false);


    if (empty($modSettings['april_fools_enabled'])) {
        return;
    }

    add_integration_function('integrate_load_theme', 'loadAprilFoolsAssets', false);
    add_integration_function('integrate_menu_buttons', 'addAprilFoolsCopyright', false);
    add_integration_function('integrate_menu_buttons', 'addAprilFoolsForUsers', false);
    add_integration_function('integrate_load_theme', 'addAprilFoolsForCurrentUser', false);
}


/**
 * Load mod assets
 */
function loadAprilFoolsAssets()
{
    global $modSettings, $context, $language, $txt;

    // Is it test mode?
    if (!empty($modSettings['april_fools_test_mode']) && empty($context['user']['is_admin'])) {
        return false;
    }

    if (!empty($modSettings['april_fools_avatar_flip'])) {
        $context['html_headers'] .= '
    <style>
        img.avatar {
             transform: scale' . ($modSettings['april_fools_avatar_flip'] == 1 ? '(-1, 1)' : '(1, -1)') . ' !important; 
        }
    </style>';
    }

    if (!empty($modSettings['april_fools_funny_dates']) && !empty($language) && ($language == 'russian' || $language == 'russian-utf8')) {

        loadLanguage('AprilFools/AprilFools');

        $txt['days'] = $txt['april_fools_funny_days'];
        $txt['months'] = $txt['months_short'] = $txt['april_fools_funny_months'];
        $txt['months_titles'] = $txt['april_fools_funny_months_titles'];
    }

    return false;
}


/**
 * Add april fools for this topic page users
 */
function addAprilFoolsForUsers()
{
    global $modSettings, $context, $user_profile;

    // Is it test mode?
    if (!empty($modSettings['april_fools_test_mode']) && empty($context['user']['is_admin'])) {
        return false;
    }

    if (is_array($user_profile)) {
        foreach (array_keys($user_profile) as $user_id) {

            if (!empty($modSettings['april_fools_name_flip'])) {
                $user_profile[$user_id]['real_name'] = flipAprilFoolsName($user_profile[$user_id]['real_name']);
            }

            if (!empty($modSettings['april_fools_name_fl_flip'])) {
                $user_profile[$user_id]['real_name'] = flipAprilFoolsNameFL($user_profile[$user_id]['real_name']);
            }
        }
    }
}


/**
 * Add gravatar to forum header for current member
 * @return bool
 */
function addAprilFoolsForCurrentUser()
{
    global $modSettings, $context, $user_info;

    // Is it test mode?
    if (!empty($modSettings['april_fools_test_mode']) && empty($context['user']['is_admin'])) {
        return false;
    }

    if (!empty($modSettings['april_fools_name_flip'])) {
        $user_info['name'] = flipAprilFoolsName($user_info['name']);
    }

    if (!empty($modSettings['april_fools_name_fl_flip'])) {
        $user_info['name'] = flipAprilFoolsNameFL($user_info['name']);
    }

}


/**
 * Flip member name
 * @param string $name
 * @return string
 */
function flipAprilFoolsName($name = '')
{
    global $smcFunc;

    return $smcFunc['ucfirst'](
        implode('', array_reverse(
                preg_split('//u', $name, -1, PREG_SPLIT_NO_EMPTY))
        )
    );
}


/**
 * Flip member name
 * @param string $name
 * @return string
 */
function flipAprilFoolsNameFL($name = '')
{
    global $smcFunc;

    $name = preg_split('//u', $name, -1, PREG_SPLIT_NO_EMPTY);
    $first = array_shift($name);
    $last = array_pop($name);

    array_unshift($name, $last);
    array_push($name, $first);

    return $smcFunc['ucfirst'](
        implode('', $name)
    );
}


/**
 * Add mod copyright to the forum credit's page
 */
function addAprilFoolsCopyright()
{
    global $context;

    if ($context['current_action'] == 'credits') {
        $context['copyrights']['mods'][] = '<a href="http://mysmf.net/mods/april-fools" target="_blank">April Fools</a> &copy; 2018, digger';
    }
}
