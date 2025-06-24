<?php

namespace Astrogoat\GoogleSSO\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;

class GoogleSSOController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider()
    {
        Socialite::driver('google')->redirect();
        dd('here');
//        return Socialite::driver('google')->redirect();
    }

    /**
     * Authenticate via Google OAuth.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();
        $user = User::where('email', $googleUser->email)->first();

        if (!$user ) {
            $failureReason[] = 'Could not find an account please contact an administrator.';
        }

        if ($invalidDomain = !Str::contains($user, '3zbrands.com')) {
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
        return redirect('/');
    }
}
