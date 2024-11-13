<?php

use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/auth/redirect', function () {
    return Socialite::driver('github')->redirect();
});

Route::get('/auth/callback', function () {
    $githubUser = Socialite::driver('github')->user();

    $user = User::firstOrCreate([
        'github_id' => $githubUser->getId(),
    ], [
        'name' => $githubUser->getName(),
        'email' => $githubUser->getEmail(),
        'avatar' => $githubUser->getAvatar(),
    ]);

    Auth::login($user);

    return redirect('/'); // Redirect to the home page after login
});
