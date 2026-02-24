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
                'openwave' => 'OpenWave',
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
            'name' => 'Token Endpoint',
            'desc' => 'OpenWave vereist additionele authenticatie via een token endpoint.',
            'attributes' => ['placeholder' => 'https://website.nl'],
            'protocols' => ['https', 'http'],
            'id' => 'client_token_endpoint',
            'type' => 'text_url',
            'sanitization_cb' => function ($value) {
                return trim($value);
            }
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
            'name' => 'SSL certificaat (.cer/.crt)',
            'desc' => 'Voer het pad in naar het SSL certificaat bestand',
            'id' => 'client_ssl_public_cert_file',
            'type' => 'text',
            'sanitization_cb' => function ($value, $fieldProps, \CMB2_Field $field) {
                if (empty($value)) {
                    return '';
                }

                if ($this->fileIsReadable($value) === false) {
                    $this->generateFieldError($field, 'Het ingevoerde pad naar het SSL certificaat is ongeldig.');

                    return '';
                }

                if ($this->isValidCertificate($value) === false) {
                    $this->generateFieldError($field, 'Het ingevoerde SSL certificaat is niet leesbaar of het is geen geldig certificaat bestand.');

                    return '';
                }

                return realpath($value);
            }
        ]);

        // Is only visible when 'client_ssl_verify_enabled' is checked.
        $options->add_group_field($clients, [
            'name' => 'SSL sleutel (.key)',
            'desc' => 'Voer het pad in naar het SSL sleutel bestand',
            'id' => 'client_ssl_private_cert_file',
            'type' => 'text',
            'sanitization_cb' => function ($value, $fieldProps, \CMB2_Field $field) {
                if (empty($value)) {
                    return '';
                }

                if ($this->fileIsReadable($value) === false) {
                    $this->generateFieldError($field, 'Het ingevoerde pad naar het SSL sleutel bestand is ongeldig.');

                    return '';
                }

                return realpath($value);
            }
        ]);

        add_action('admin_notices', $this->queueAdminNotices(...));
    }

    /**
     * Check if the provided path is a valid, readable directory.
     * Prevents directory traversal by ensuring the real path starts with the provided path.
     */
    protected function fileIsReadable(string $path): bool
    {
        if (empty($path)) {
            return false;
        }

        $real = realpath($path);

        if (empty($real) || $real !== $path) {
            return false;
        }

        return file_exists($real) && is_readable($real);
    }

    /**
     * Check if the provided file is a valid SSL certificate.
     * Does not work with private keys.
     */
    protected function isValidCertificate(string $file): bool
    {
        if (empty($file)) {
            return false;
        }

        $content = @file_get_contents($file);

        // openssl_x509_checkpurpose() fails for PKIoverheid certs due to missing trust chain.
        return ! empty($content) && openssl_x509_read($content) !== false;
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
                        const secretRowSecretZrc  = group.find('[name*="[client_secret_zrc]"]').closest('.cmb-row');
                        secretRowSecretZrc.toggle(clientType === 'decosjoin');

                        const secretRowTokenEndpoint  = group.find('[name*="[client_token_endpoint]"]').closest('.cmb-row');
                        secretRowTokenEndpoint.toggle(clientType === 'openwave');
                    }

                    function initializeClientSecrets(context) {
                        const ctx = context || document;

                        $(ctx).find('.cmb-repeatable-grouping').each(function() {
                            toggleClientSecretZRC($(this));
                        });
                    }

                    // Initialize when DOM is ready.
                    $(document).ready(function() {
                        initializeClientSecrets(document);
                    });

                    // Watch for changes to client type fields.
                    $(document).on('change', '[name*="[client_type]"]', function() {
                        const group = $(this).closest('.cmb-repeatable-grouping');
                        toggleClientSecretZRC(group);
                    });

                    // Watch for new rows being added.
                    $(document).on('cmb2_add_row', function(e, newRow) {
                        initializeClientSecrets(newRow);
                    });
                })(jQuery);
            JS);
        }
    }

    protected function generateFieldError(\CMB2_Field $field, string $message): void
    {
        $transientKey = 'cmb2_errors_' . get_current_user_id();
        $current = json_decode((string) get_transient($transientKey), true);

        if (empty($current)) {
            $current = [];
        }

        $current[] = [
            'title' => sprintf('Foutmelding voor veld "%s"', esc_html($field->args['name'])),
            'message' => esc_html($message),
        ];

        set_transient($transientKey, json_encode($current), 45);
    }

    protected function queueAdminNotices(): void
    {
        $userId = get_current_user_id();
        $transientKey = 'cmb2_errors_' . $userId;
        $current = json_decode((string) get_transient($transientKey), true);

        if (empty($current)) {
            return; // Nothing to do here
        }


        foreach ($current as $error) {
            printf(
                '<div class="notice notice-error is-dismissible">
                    <p style="font-weight: bold;">%s</p>
                    <p>%s</p>
                </div>',
                esc_html($error['title'] ?? ''),
                esc_html($error['message'] ?? ''),
            );
        }

        delete_transient($transientKey);
    }
}
