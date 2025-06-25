<?php

namespace Astrogoat\GoogleSSO\Http\Controllers;

use Astrogoat\GoogleSSO\Settings\GoogleSSOSettings;
use Helix\Lego\Providers\RouteServiceProvider;
use Illuminate\Routing\Controller;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\GoogleProvider;
use Helix\Lego\Models\User;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GoogleSSOController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider()
    {
        $settings = resolve(GoogleSSOSettings::class);

        throw_if(!$settings->enabled, NotFoundHttpException::class);

        return $this->getProvider($settings)->redirect();
    }

    /**
     * Authenticate via Google OAuth.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback()
    {
        $settings = resolve(GoogleSSOSettings::class);

        throw_if(!$settings->enabled, NotFoundHttpException::class);

        $googleUser = $this->getProvider($settings)->stateless()->user();
        $user = User::where('email', $googleUser->email)->first();

        if (!$user ) {
            $failureReason[] = 'Could not find an account please contact an administrator.';
        }

        if ($invalidDomain = !Str::contains($user, Arr::map(explode(',', $settings->approved_domains), fn ($domain) => trim($domain)))) {
            $failureReason[] = 'You must use an approved domain to login with google.';
        }

        if ($invalidDomain || !$user) {
            throw ValidationException::withMessages([
                'email' => implode(' ', $failureReason),
            ])->redirectTo('/login');
        }

        $user->password = bcrypt(Str::random(20));
        $user->save();

        Auth::login($user);
        return redirect(RouteServiceProvider::$home);
    }

    private function getProvider(GoogleSSOSettings $settings)
    {
        return Socialite::buildProvider(GoogleProvider::class, [
            'client_id' => $settings->client_id,
            'client_secret' => $settings->client_secret,
            'redirect' => route('google.callback'),
        ]);
    }
}
