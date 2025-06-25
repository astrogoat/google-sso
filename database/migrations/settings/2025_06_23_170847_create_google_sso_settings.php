<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('google-sso.enabled', "");
        $this->migrator->add('google-sso.client_id', "");
        $this->migrator->add('google-sso.client_secret', "", true);
        $this->migrator->add('google-sso.force_password_reset', false);
        $this->migrator->add('google-sso.approved_domains', false);
    }

    public function down()
    {
        $this->migrator->delete('google-sso.enabled');
        $this->migrator->delete('google-sso.client_id');
        $this->migrator->delete('google-sso.client_secret');
        $this->migrator->delete('google-sso.force_password_reset');
        $this->migrator->delete('google-sso.approved_domains');
    }
};
