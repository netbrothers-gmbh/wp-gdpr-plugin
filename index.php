<?php

/**
 * Plugin Name:       NetBrothers
 * Plugin URI:        https://netbrothers.de/wordpress-gdpr-plugin
 * Description:       Wir bleiben DSGVO konform.
 * Version:           1.0.0
 * Requires at least: 5.6
 * Requires PHP:      7.2
 * Author:            Maxim Uhlemann
 * Author URI:        https://netbrothers.de
 * License:           MIT
 * Text Domain:       netbrothers-gdpr
 * Domain Path:       /locales
 */


function is_chatbot_enabled()
{
    return get_option('nb-enable-chatbot') === '1'
        && !empty(get_option('nb-chatbot-id'))
        && !empty(get_option('nb-chatbot-remote'));
}

function get_chatbot_cookie_name()
{
    $name = get_option('nb-chatbot-cookie', 'netbrothers_allow_chatbot');
    if (empty($name)) {
        return 'netbrothers_allow_chatbot';
    }
    return $name;
}

function is_chatbot_allowed()
{
    return isset($_COOKIE[get_chatbot_cookie_name()]) || $_GET['enable-chatbot-cookie'] === '1';
}

if ($_GET['enable-chatbot-cookie'] === '1') {
    setcookie(get_chatbot_cookie_name(), '1');
}


require_once 'bot.php';
require_once 'settings.php';
