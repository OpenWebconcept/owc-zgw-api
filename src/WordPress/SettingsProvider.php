<?php

declare(strict_types=1);

namespace OWC\ZGW\WordPress;

use OWC\ZGW\Support\ServiceProvider;

class SettingsProvider extends ServiceProvider
{
    public function register(): void
    {
        add_action('cmb2_admin_init', $this->addSettingsFields(...));
        add_action('admin_footer', $this->registerSettingsPageScripts(...));
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
            'desc' => 'Unieke naam voor dit register. Alleen kleine letters, cijfers en koppeltekens zijn toegestaan.',
            'id' => 'name',
            'type' => 'text',
            'sanitization_cb' => function ($value) {
                $value = strtolower($value);

                // Only allow lowercase letters, numbers and hyphens.
                $value = preg_replace('/[^a-z0-9-]/', '', $value);

                return $value;
            },
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

        $options->add_group_field($clients, [
            'name' => 'SSL certificaat verificatie',
            'desc' => 'Schakel SSL certificaat verificatie in of uit',
            'id' => 'client_ssl_verify_enabled',
            'type' => 'checkbox',
            'default' => 0,
        ]);

        // Is only visible when 'client_ssl_verify_enabled' is checked.
        $options->add_group_field($clients, [
            'name' => 'Certificaat pad',
            'desc' => 'Absolute pad naar de locatie van de certificaten',
            'id' => 'client_ssl_cert_path',
            'type' => 'text',
        ]);

        // Is only visible when 'client_ssl_verify_enabled' is checked.
        $options->add_group_field($clients, [
            'name' => 'SSL certificaat (.cer/.csr)',
            'desc' => 'Selecteer het SSL certificaat bestand',
            'id' => 'client_ssl_public_cert_file',
            'type' => 'select',
            'options_cb' => function ($field) {
                return $this->getCertificateFilesOptions($field, [ 'cer', 'crt' ]);
            },
        ]);

        // Is only visible when 'client_ssl_verify_enabled' is checked.
        $options->add_group_field($clients, [
            'name' => 'SSL sleutel (.key)',
            'desc' => 'Selecteer het SSL sleutel bestand',
            'id' => 'client_ssl_private_cert_file',
            'type' => 'select',
            'options_cb' => function ($field) {
                return $this->getCertificateFilesOptions($field, [ 'key' ]);
            },
        ]);
    }

    public function getCertificateFilesOptions($field, array $extensions): array
    {
        $options = ['' => 'Selecteer een bestand'];

        $groupValues = $field->group ? $field->group->value : [];
        $rowIndex = $field->group ? $field->group->index : null;

        $path = '';

        if (is_int($rowIndex) && isset($groupValues[ $rowIndex ]['client_ssl_cert_path'])) {
            $path = rtrim($groupValues[ $rowIndex ]['client_ssl_cert_path'], '/\\');
        }

        if ('' === $path || ! is_dir($path) || ! is_readable($path)) {
            return $options;
        }

        foreach ($extensions as $ext) {
            foreach (glob($path . '/*.' . $ext) as $file) {
                $basename = basename($file);
                $options[ $file ] = $basename;
            }
        }

        return $options;
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
                        const secretRow  = group.find('[name*="[client_secret_zrc]"]').closest('.cmb-row');
                        secretRow.toggle(clientType === 'decosjoin');
                    }

                    function initializeClientSecrets(context) {
                        const ctx = context || document;

                        $(ctx).find('.cmb-repeatable-grouping').each(function() {
                            toggleClientSecretZRC($(this));
                        });
                    }

                    function toggleSslFieldsForGroup(group) {
                        const checkbox = group.find('input[type="checkbox"][name*="[client_ssl_verify_enabled]"]');

                        if (! checkbox.length) {
                            return;
                        }

                        const isChecked = checkbox.is(':checked');

                        const pathRow = group.find('[name*="[client_ssl_cert_path]"]').closest('.cmb-row');
                        const certRow = group.find('[name*="[client_ssl_public_cert_file]"]').closest('.cmb-row');
                        const keyRow  = group.find('[name*="[client_ssl_private_cert_file]"]').closest('.cmb-row');

                        pathRow.toggle(isChecked);
                        certRow.toggle(isChecked);
                        keyRow.toggle(isChecked);
                    }

                    function initSslGroups(context) {
                        const ctx = context || document;

                        $(ctx).find('.cmb-repeatable-grouping').each(function() {
                            const group = $(this);

                            toggleSslFieldsForGroup(group);

                            group
                                .find('input[type="checkbox"][name*="[client_ssl_verify_enabled]"]')
                                .off('change.cbm2SslToggle')
                                .on('change.cbm2SslToggle', function() {
                                    toggleSslFieldsForGroup(group);
                                });
                        });

                        // Fallback voor non-group scenario (mocht je ssl-fields ooit los gebruiken)
                        const standaloneCheckbox = $(ctx)
                            .find('input[type="checkbox"][name*="[client_ssl_verify_enabled]"]')
                            .not($(ctx).find('.cmb-repeatable-grouping input[type="checkbox"][name*="[client_ssl_verify_enabled]"]'));

                        if (standaloneCheckbox.length) {
                            const pathRow = $(ctx).find('[name*="[client_ssl_cert_path]"]').closest('.cmb-row');
                            const certRow = $(ctx).find('[name*="[client_ssl_public_cert_file]"]').closest('.cmb-row');
                            const keyRow  = $(ctx).find('[name*="[client_ssl_private_cert_file]"]').closest('.cmb-row');

                            const isChecked = standaloneCheckbox.is(':checked');

                            pathRow.toggle(isChecked);
                            certRow.toggle(isChecked);
                            keyRow.toggle(isChecked);

                            standaloneCheckbox
                                .off('change.cbm2SslToggleStandalone')
                                .on('change.cbm2SslToggleStandalone', function() {
                                    const c = $(this).is(':checked');
                                    pathRow.toggle(c);
                                    certRow.toggle(c);
                                    keyRow.toggle(c);
                                });
                        }
                    }

                    // Initialize when DOM is ready.
                    $(document).ready(function() {
                        initializeClientSecrets(document);
                        initSslGroups(document);
                    });

                    // Watch for changes to client type fields.
                    $(document).on('change', '[name*="[client_type]"]', function() {
                        const group = $(this).closest('.cmb-repeatable-grouping');
                        toggleClientSecretZRC(group);
                    });

                    // Watch for new rows being added.
                    $(document).on('cmb2_add_row', function(e, newRow) {
                        initializeClientSecrets(newRow);
                        initSslGroups(newRow);
                    });
                })(jQuery);
            JS);
        }
    }
}
