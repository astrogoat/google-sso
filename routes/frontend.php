<?php
Route::get('sso/google', Astrogoat\GoogleSSO\Http\Controllers\GoogleSSOController::class.'@redirectToProvider')->name('google');
Route::get('sso/google/callback', Astrogoat\GoogleSSO\Http\Controllers\GoogleSSOController::class.'@handleProviderCallback')->name('google.callback');
