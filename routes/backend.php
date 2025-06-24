<?php

Route::get('google', Astrogoat\GoogleSSO\Http\Controllers\GoogleSSOController::class.'@redirectToProvider')->name('google');
//Route::get('google/callback', );
