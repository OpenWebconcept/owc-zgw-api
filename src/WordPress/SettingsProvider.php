<?php

declare(strict_types=1);

namespace OWC\ZGW\WordPress;

use OWC\ZGW\Support\ServiceProvider;

class SettingsProvider extends ServiceProvider
{
    public function register(): void
    {
        add_action('cmb2_admin_init', [$this, 'addSettingsFields']);
        add_action('admin_footer', [$this, 'registerSettingsPageScripts']);
    }

    public function addSettingsFields(): void
    {
        $options = new_cmb2_box([
            'id' => 'zgw-api-settings',
            'title' => 'ZGW API instellingen',
            'object_types' => ['options-page'],

            'option_key' => 'zgw_api_settings',
            'parent_slug' => 'options-general.php',
            'capability' => 'manage_options',
        ]);

        $clients = $options->add_field([
            'id' => 'zgw-api-configured-clients',
            'type' => 'group',
            'description' => 'Configureer ZGW registers',
            'options' => [
                'group_title' => 'ZGW Register {#}',
                'add_button' => 'Voeg nog een register toe',
                'remove_button' => 'Verwijder register'
            ],
        ]);

        $options->add_group_field($clients, [
            'name' => 'Register naam (uniek)',
            'id' => 'name',
            'type' => 'text',
        ]);

        $options->add_group_field($clients, [
            'name' => 'Register type',
            'id' => 'client_type',
            'type' => 'select',
            'default' => 'openzaak',
            'options' => [
                'openzaak' => 'OpenZaak',
                'xxllnc' => 'XXLLNC',
                'rxmission' => 'RxMission',
                'decosjoin' => 'Decos JOIN',
                'procura' => 'Procura',
            ]
        ]);

        $options->add_group_field($clients, [
            'name' => 'Register versie',
            'id' => 'client_version',
            'type' => 'select',
            'default' => 'pre-150',
            'options' => [
                'pre-150' => 'ZGW 1.4.1 of eerder',
                '150' => 'ZGW 1.5.0',
                '151' => 'ZGW 1.5.1',
            ]
        ]);

        $options->add_group_field($clients, [
            'name' => 'Zaken (ZRC) API endpoint',
            'desc' => 'Bijvoorbeeld: https://test.openzaak.nl/zaken/api/v1',
            'attributes' => ['placeholder' => 'https://website.nl'],
            'protocols' => ['https', 'http'],
            'id' => 'zrc_endpoint',
            'type' => 'text_url',
        ]);

        $options->add_group_field($clients, [
            'name' => 'Catalogus (ZTC) API endpoint',
            'desc' => 'Bijvoorbeeld: https://test.openzaak.nl/catalogi/api/v1',
            'attributes' => ['placeholder' => 'https://website.nl'],
            'protocols' => ['https', 'http'],
            'id' => 'ztc_endpoint',
            'type' => 'text_url',
        ]);

        $options->add_group_field($clients, [
            'name' => 'Documenten (DRC) API endpoint',
            'desc' => 'Bijvoorbeeld: https://test.openzaak.nl/documenten/api/v1',
            'attributes' => ['placeholder' => 'https://website.nl'],
            'protocols' => ['https', 'http'],
            'id' => 'drc_endpoint',
            'type' => 'text_url',
        ]);

        $options->add_group_field($clients, [
            'name' => 'Client identifier',
            'desc' => 'Laat leeg als het register alleen een API key vereist',
            'id' => 'client_id',
            'type' => 'text',
        ]);

        $options->add_group_field($clients, [
            'name' => 'Client secret',
            'id' => 'client_secret',
            'type' => 'text',
            'attributes' => ['type' => 'password'],
        ]);

        $options->add_group_field($clients, [
            'name' => 'Client secret (ZRC)',
            'id' => 'client_secret_zrc',
            'type' => 'text',
            'attributes' => ['type' => 'password'],
        ]);
    }

    public function registerSettingsPageScripts(): void
    {
        $screen = get_current_screen();

        if ($screen && $screen->id === 'settings_page_zgw_api_settings') {
            wp_enqueue_script('jquery');

            wp_register_script('zgw-api-settings', '', [], false, true);
            wp_enqueue_script('zgw-api-settings');

            wp_add_inline_script('zgw-api-settings', <<<JS
                (function($) {
                    'use strict';
                    
                    function toggleClientSecretZRC(group) {
                        const clientType = group.find('[name*="[client_type]"]').val();
                        const secretRow = group.find('[name*="[client_secret_zrc]"]').closest('.cmb-row');
                        secretRow.toggle(clientType === 'decosjoin');
                    }
                    
                    function initializeClientSecrets() {
                        $('.cmb-repeatable-grouping').each(function() {
                            toggleClientSecretZRC($(this));
                        });
                    }
                    
                    // Initialize when DOM is ready.
                    $(document).ready(initializeClientSecrets);
                    
                     // Watch for changes to client type fields.
                    $(document).on('change', '[name*="[client_type]"]', function() {
                        const group = $(this).closest('.cmb-repeatable-grouping');
                        toggleClientSecretZRC(group);
                    });
                })(jQuery);
            JS);
        }
    }
}
