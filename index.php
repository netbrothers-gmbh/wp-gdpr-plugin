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
 */


function is_chatbot_enabled()
{
    return get_option('nb-enable-chatbot') === '1'
        && !empty(get_option('nb-chatbot-id'))
        && !empty(get_option('nb-chatbot-remote'));
}

function get_chatbot_cookie_name()
{
    $name = get_option('nb-chatbot-cookie', 'allow_chatbot');
    if (empty($name)) {
        return 'allow_chatbot';
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

add_action('wp_body_open', 'nb_add_chatbot');
function nb_add_chatbot()
{
    if (!is_chatbot_enabled()) {
        return;
    }
    ?>
    <style id="eb7-styles">

        .eb7-snippet--wrapper {
            border-radius: 10px;
            box-shadow: 0 5px 40px rgba(0, 0, 0, .36);
            overflow: hidden;
            position: fixed;
            bottom: 80px;
            right: 10px;
            display: none;
            -webkit-background-clip: padding-box;
            background-clip: padding-box;
            z-index: 99999999;
            background-color: white;
        }

        #ebot7-wrapper.loading {
            background-image: url('data:image/svg+xml,%3Csvg width="50" height="50" viewBox="0 0 135 140" xmlns="http://www.w3.org/2000/svg" fill="%233c8dbc"%3E%3Crect y="10" width="15" height="120" rx="6"%3E%3Canimate attributeName="height" begin="0.5s" dur="1s" values="120;110;100;90;80;70;60;50;40;140;120" calcMode="linear" repeatCount="indefinite" /%3E%3Canimate attributeName="y" begin="0.5s" dur="1s" values="10;15;20;25;30;35;40;45;50;0;10" calcMode="linear" repeatCount="indefinite" /%3E%3C/rect%3E%3Crect x="30" y="10" width="15" height="120" rx="6"%3E%3Canimate attributeName="height" begin="0.25s" dur="1s" values="120;110;100;90;80;70;60;50;40;140;120" calcMode="linear" repeatCount="indefinite" /%3E%3Canimate attributeName="y" begin="0.25s" dur="1s" values="10;15;20;25;30;35;40;45;50;0;10" calcMode="linear" repeatCount="indefinite" /%3E%3C/rect%3E%3Crect x="60" width="15" height="140" rx="6"%3E%3Canimate attributeName="height" begin="0s" dur="1s" values="120;110;100;90;80;70;60;50;40;140;120" calcMode="linear" repeatCount="indefinite" /%3E%3Canimate attributeName="y" begin="0s" dur="1s" values="10;15;20;25;30;35;40;45;50;0;10" calcMode="linear" repeatCount="indefinite" /%3E%3C/rect%3E%3Crect x="90" y="10" width="15" height="120" rx="6"%3E%3Canimate attributeName="height" begin="0.25s" dur="1s" values="120;110;100;90;80;70;60;50;40;140;120" calcMode="linear" repeatCount="indefinite" /%3E%3Canimate attributeName="y" begin="0.25s" dur="1s" values="10;15;20;25;30;35;40;45;50;0;10" calcMode="linear" repeatCount="indefinite" /%3E%3C/rect%3E%3Crect x="120" y="10" width="15" height="120" rx="6"%3E%3Canimate attributeName="height" begin="0.5s" dur="1s" values="120;110;100;90;80;70;60;50;40;140;120" calcMode="linear" repeatCount="indefinite" /%3E%3Canimate attributeName="y" begin="0.5s" dur="1s" values="10;15;20;25;30;35;40;45;50;0;10" calcMode="linear" repeatCount="indefinite" /%3E%3C/rect%3E%3C/svg%3E');
            background-repeat: no-repeat;
            background-position: center;
        }

        .eb7-snippet--button {
            background: #3c8dbc;
            background-size: 32px 36px;
            background-repeat: no-repeat;
            background-position: center 12px;
            position: fixed;
            bottom: 10px;
            right: 10px;
            width: 60px;
            height: 60px;
            border-radius: 100%;
            cursor: pointer;
            text-align: center;
            z-index: 99999999;
        }

        .eb7-snippet--button.chat-open {
            background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAACXBIWXMAAAsTAAALEwEAmpwYAAAAqUlEQVRYhe2Wyw2FMAwEEaIiOk0ddJNOUsFwyYW/HdvKk172vjsjISJP08jIKcAKZKAACVgMW0vdKHVzlZQyx2wtEhW+nbaypFi4RiXxAAcoknK6KYolXuAAyWL/KWHpmofc4C2D7nDNcBhcIREHF0rEwhsk/OEKCTV8DjGNSNdPoID7S3zAY39DATzuIdIMu0u0DLpJWIZcJPiBg6T7Sdb9KO17lo/8XXZChvlJa6YNTwAAAABJRU5ErkJggg==');
            background-size: 32%;
            background-position: center;
        }

        .eb7-snippet--button.chat-closed {
            background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAABmJLR0QA/wD/AP+gvaeTAAACUklEQVRYhe2WT2tTQRTFz8RE2wRbrAsFwcaKikWFIggqKohFEFyJaCkI6lJQqf0QrVs/g59ApBsX9V+LulCiUinRdlGqaMGF1YiQ/ly8W5i8vLwkr6EI5sAwvJl77jnz3p15I7XRxv8OFzcJbJB0XNJ5ScckbZO03aa/WJuW9EDSc+dcuSWugI3AHeAzjWMBuG6m1yS+D3jnJf4AjANngDyQtZa3sXGLWcUbYG9S8X5gyRIVgUtAqgFeymKLxl0C9jcrnvVWMglsSbCAHuOuvrnOZsgjRpwDcs2Ke3lylgPgto1trVsbwCsjXUgq7uW6aLle2HM/8AzoiyP9NNLmFhjotlzL9pz1dspOP9YvsJL1HWs1EIFN1u+Q9BBIRxmYsf50CwTPWv/e+l3e3AFJV6oYwDWvehN/hlAR3rKxUSrxJIqYAQoW8AjoTiDub8MZoBNwwGzIwLdaCXqBRQuaBwYbFE4Bl4GPxv2KHUTATaoRbcAIeWDKCx4FDgKngN32inNAHzAI3KXyKH4N7LFch4HfEQaqP0HIRBfwy4IngD8RScJYIKijlOU4CXyvEXu1noGxEKEMvCQ465etzQJP7Q2cwE46gloaAUo1xN8CmTjxIWDFI0wCA7GOA14HMEzlnzSMRaDX51VdSIA5ST8kTUi675wreHOrztOSehRcUI5IOqrg0hL3A5uSNOycm6+3mLhVZoAbNHdRgaCeuhILRxhJA+eAe8C0GSoR1EaRoF7KIRNjLTPQoMkB4LFnYAUYWlcTZuQQwU4pAJ/W3UAb/zz+AoF82cAVrzUYAAAAAElFTkSuQmCC');
        }

        .eb7-snippet--button.loading {
            background-image: url('data:image/svg+xml,%3Csvg width="135" height="140" viewBox="0 0 135 140" xmlns="http://www.w3.org/2000/svg" fill="%23fff"%3E%3Crect y="10" width="15" height="120" rx="6"%3E%3Canimate attributeName="height" begin="0.5s" dur="1s" values="120;110;100;90;80;70;60;50;40;140;120" calcMode="linear" repeatCount="indefinite" /%3E%3Canimate attributeName="y" begin="0.5s" dur="1s" values="10;15;20;25;30;35;40;45;50;0;10" calcMode="linear" repeatCount="indefinite" /%3E%3C/rect%3E%3Crect x="30" y="10" width="15" height="120" rx="6"%3E%3Canimate attributeName="height" begin="0.25s" dur="1s" values="120;110;100;90;80;70;60;50;40;140;120" calcMode="linear" repeatCount="indefinite" /%3E%3Canimate attributeName="y" begin="0.25s" dur="1s" values="10;15;20;25;30;35;40;45;50;0;10" calcMode="linear" repeatCount="indefinite" /%3E%3C/rect%3E%3Crect x="60" width="15" height="140" rx="6"%3E%3Canimate attributeName="height" begin="0s" dur="1s" values="120;110;100;90;80;70;60;50;40;140;120" calcMode="linear" repeatCount="indefinite" /%3E%3Canimate attributeName="y" begin="0s" dur="1s" values="10;15;20;25;30;35;40;45;50;0;10" calcMode="linear" repeatCount="indefinite" /%3E%3C/rect%3E%3Crect x="90" y="10" width="15" height="120" rx="6"%3E%3Canimate attributeName="height" begin="0.25s" dur="1s" values="120;110;100;90;80;70;60;50;40;140;120" calcMode="linear" repeatCount="indefinite" /%3E%3Canimate attributeName="y" begin="0.25s" dur="1s" values="10;15;20;25;30;35;40;45;50;0;10" calcMode="linear" repeatCount="indefinite" /%3E%3C/rect%3E%3Crect x="120" y="10" width="15" height="120" rx="6"%3E%3Canimate attributeName="height" begin="0.5s" dur="1s" values="120;110;100;90;80;70;60;50;40;140;120" calcMode="linear" repeatCount="indefinite" /%3E%3Canimate attributeName="y" begin="0.5s" dur="1s" values="10;15;20;25;30;35;40;45;50;0;10" calcMode="linear" repeatCount="indefinite" /%3E%3C/rect%3E%3C/svg%3E');
        }

        .eb7-snippet--toggle-chat {
            display: none;
            height: 0;
            opacity: 0;
            overflow: hidden;
            transition: height 350ms ease-in-out, opacity 750ms ease-in-out;
        }

        .eb7-snippet--toggle-chat.is-visible {
            display: block;
            height: auto;
            opacity: 1;
        }

        .eb7-snippet--mobile-header {
            height: 44px;
            background: #ddd;
            display: none;
        }

        .eb7-snippet--mobile-close-button {
            width: 44px;
            height: 44px;
            float: right;
            position: unset;
            border-radius: 0;
            background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAACXBIWXMAAAsTAAALEwEAmpwYAAAAqUlEQVRYhe2Wyw2FMAwEEaIiOk0ddJNOUsFwyYW/HdvKk172vjsjISJP08jIKcAKZKAACVgMW0vdKHVzlZQyx2wtEhW+nbaypFi4RiXxAAcoknK6KYolXuAAyWL/KWHpmofc4C2D7nDNcBhcIREHF0rEwhsk/OEKCTV8DjGNSNdPoID7S3zAY39DATzuIdIMu0u0DLpJWIZcJPiBg6T7Sdb9KO17lo/8XXZChvlJa6YNTwAAAABJRU5ErkJggg==');
            background-size: 32%;
            background-position: center;
        }

        iframe.eb7-snippet--iframe {
            height: 100%;
        }

        @media (max-width: 540px) {
            .eb7-snippet--wrapper {
                top: 0;
                right: 0;
                left: 0;
                bottom: 0;
                border-radius: 0;
            }

            .eb7-snippet--mobile-header {
                display: block;
            }

            iframe.eb7-snippet--iframe {
                height: calc(100% - 44px);
            }

            .eb7-snippet--button.chat-open {
                opacity: 0;
            }
        }

        .eb7-snippet--button {
            background-color: #217bc9;
        }

    </style>
    <div id="ebot7-open" class="eb7-snippet--button chat-closed" style=""></div>
    <?php
    if (!is_chatbot_allowed()) {
        ?>
        <script>
            // clean url
            window.onload = function () {
                document.getElementById('ebot7-open').addEventListener('mouseup', function () {
                    if (!confirm('Chatbot aktivieren? Ihre Nutzungsdaten werden an e-bot7 und ggf. Dritte weitergegeben.')) {
                        return;
                    }
                    fetch(window.location.href + '?enable-chatbot-cookie=1');
                    window.eb7Init = function() {
                        ebot7.init({
                            botId: '<?= get_option('nb-chatbot-id') ?>'
                        });
                    };

                    (function (d, s, id) {
                        var fjs = d.getElementsByTagName(s)[0];
                        if (d.getElementById(id)) return;
                        var js = d.createElement(s); js.id = id;
                        js.src = 'https://<?= get_option('nb-chatbot-remote') ?>/embed.js';
                        fjs.parentNode.insertBefore(js, fjs);
                        js.onload = function() { window.ebot7.toggleChat() }
                    })(document, 'script', 'eb7-script');
                });
            }
        </script><?php
        return;
    }
    ?>
    <script>
        window.eb7Init = function () {
            ebot7.init({botId: '<?= get_option('nb-chatbot-id') ?>'});
            delete window.eb7Init;
        };
    </script>
    <script id="eb7-script" src="https://<?= get_option('nb-chatbot-remote') ?>/embed.js"></script>
    <?php
}

require_once 'settings.php';
