<?php

add_action('admin_menu', 'netbrothers_gdpr_settings_menu');
function netbrothers_gdpr_settings_menu()
{
    add_menu_page(
        'NetBrothers GDPR Einstellungen', // page_title
        'NetBrothers GDPR', // menu_title
        'manage_options', // capability
        'netbrothers-gdpr-settings', // menu_slug
        'netbrothers_gdpr_settings_page', // callback
        'dashicons-media-code', // icon
        4 // position
    );
    add_action('admin_init', 'update_netbrothers_gdpr_settings');
}

function netbrothers_gdpr_settings_page()
{
    ?>
    <h1>NetBrothers GDPR Einstellungen</h1>
    <form method="POST" action="options.php">
        <input name="nb-enable-chatbot"
               id="nb-enable-chatbot"
               type="checkbox"
               value="1"
               class="code"
            <?= checked(1, get_option('nb-enable-chatbot'), false) ?>
        /> Chatbot aktvieren
        <br><br>
        <label for="nb-chatbot-id" class="nb-settings-label">Chatbot-ID*</label>
        <input type="text"
               class="nb-settings-input"
               id="nb-chatbot-id"
               name="nb-chatbot-id"
               placeholder="z.B. 601318858256c8243d61601d"
               value="<?= get_option('nb-chatbot-id') ?>">
        <a class="nb-settings-description"
           target="_blank"
           href="https://netbrothers.de/wordpress-gdpr-plugin#wie-finde-ich-meine-chatbot-id">
            Wie finde ich meine Chatbot-ID?
        </a>
        <br><br>
        <label for="nb-chatbot-js" class="nb-settings-label">e-bot7 Instanz*</label>
        <input type="text"
               class="nb-settings-input"
               id="nb-chatbot-remote"
               name="nb-chatbot-remote"
               placeholder="z.B. main-botconsole.production.e-bot7.de"
               value="<?= get_option('nb-chatbot-remote') ?>">
        <a class="nb-settings-description"
           target="_blank"
           href="https://netbrothers.de/wordpress-gdpr-plugin#wie-finde-ich-meine-chatbot-instanz">
            Wie finde ich meine e-bot7 Instanz?
        </a>
        <?php
        if (is_chatbot_enabled()) {
            ?>
            <br><br>
            <h2>Profi-Einstellungen</h2>
            <label for="nb-chatbot-cookie" class="nb-settings-label">Chatbot-Cookie</label>
            <input type="text"
                   class="nb-settings-input"
                   id="nb-chatbot-cookie"
                   name="nb-chatbot-cookie"
                   placeholder="allow_chatbot"
                   value="<?= get_option('nb-chatbot-cookie', 'allow_chatbot') ?>">
            <a class="nb-settings-description"
               target="_blank"
               href="https://netbrothers.de/wordpress-gdpr-plugin#was-ist-mein-chatbot-cookie">
                Was ist mein Chatbot-Cookie?
            </a>
            <br><br>
            <input name="nb-chatbot-hide-search"
                   id="nb-chatbot-hide-search"
                   type="checkbox"
                   disabled
                   value="1"
                   class="code"
                <?= checked(1, get_option('nb-chatbot-hide-search'), false) ?>
            /> Für Suchmaschinen ausblenden
            <?php
        }
        settings_fields('netbrothers-gdpr-settings');
        do_settings_sections('netbrothers-gdpr-settings');
        submit_button();
        if (is_chatbot_enabled()) {
            echo 'Status: Der Chatbot wird angezeigt.';
        } else {
            echo 'Status: Der Chatbot wird nicht angezeigt.';
            if (get_option('nb-enable-chatbot') === '1') {
                echo ' Bitte ergänzen Sie die Chatbot-ID und e-bot7 Instanz.';
            }
        }
        ?>
    </form>
    <style>
        .nb-settings-label, .nb-settings-input, .nb-settings-description {
            width: 30%;
            display: inline-block;
        }
    </style>
    <?php

}

function update_netbrothers_gdpr_settings()
{
    register_setting('netbrothers-gdpr-settings', 'nb-enable-chatbot');
    register_setting('netbrothers-gdpr-settings', 'nb-chatbot-id');
    register_setting('netbrothers-gdpr-settings', 'nb-chatbot-cookie');
    register_setting('netbrothers-gdpr-settings', 'nb-chatbot-remote');
    register_setting('netbrothers-gdpr-settings', 'nb-chatbot-hide-search');
}

