<?php

namespace Astrogoat\GoogleSSO;

use Helix\Lego\Apps\App;
use Helix\Lego\Apps\AppPackageServiceProvider;
use Laravel\Socialite\Contracts\Factory;
use Laravel\Socialite\SocialiteManager;
use Spatie\LaravelPackageTools\Package;
use Astrogoat\GoogleSSO\Settings\GoogleSSOSettings;

class GoogleSSOServiceProvider extends AppPackageServiceProvider
{
    public function registerApp(App $app): App
    {
        require __DIR__.'/../vendor/autoload.php';

        $this->app->singleton(Factory::class, function ($app) {
            return new SocialiteManager($app);
        });

        return $app
            ->name('google-sso')
            ->settings(GoogleSSOSettings::class)
            ->migrations([
                __DIR__ . '/../database/migrations',
                __DIR__ . '/../database/migrations/settings',
            ])
            ->backendRoutes(__DIR__.'/../routes/backend.php')
            ->frontendRoutes(__DIR__.'/../routes/frontend.php');

    }

    public function configurePackage(Package $package): void
    {
        $package->name('google-sso')->hasConfigFile()->hasViews();
    }
}
