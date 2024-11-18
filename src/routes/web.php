<?php

use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Controllers\SnippetController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\SuggestionController;

Route::get('/', function () {
    return view('home');
});

Route::get('/auth/redirect', function () {
    return Socialite::driver('github')->redirect();
})->name('github.login');

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

    return redirect('/');
});

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');

// Reorder the routes to prevent conflict with "/snippets/{id}"
Route::middleware(['auth'])->group(function () {
    Route::get('/snippets/create', [SnippetController::class, 'create'])->name('snippets.create');
    Route::resource('snippets', SnippetController::class)->except(['index', 'show']);
    Route::post('/snippets/{snippet}/suggest', [SuggestionController::class, 'store'])->name('suggestions.store');
});

Route::resource('snippets', SnippetController::class)->only(['index', 'show']);
Route::post('/snippets/{snippet}/rate', [RatingController::class, 'store'])->name('ratings.store');
