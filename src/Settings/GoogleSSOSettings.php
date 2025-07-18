<?php

namespace Astrogoat\GoogleSSO\Settings;

use Helix\Lego\Settings\AppSettings;
use Illuminate\Validation\Rule;

class GoogleSSOSettings extends AppSettings
{
    public string $client_id;
    public string $client_secret;
    public bool $force_password_reset;
    public string $approved_domains;

    public function rules(): array
    {
        return [
            'client_id' => Rule::requiredIf($this->enabled === true),
            'client_secret' => Rule::requiredIf($this->enabled === true),
            'force_password_reset' => Rule::requiredIf($this->enabled === true),
            'approved_domains' => Rule::requiredIf($this->enabled === true),
        ];
    }

    public function description(): string
    {
        return 'Allow single sign on from Google. Within the approved domain list.';
    }

    public static function group(): string
    {
        return 'google-sso';
    }
}
