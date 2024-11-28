<?php

use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Controllers\SnippetController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\SuggestionController;

// Public Routes
Route::get('/', [SnippetController::class, 'index'])->name('home'); // Home route

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

    return redirect()->route('home');
});

// Logout
Route::post('/logout', function () {
    Auth::logout();
    return redirect()->route('home');
})->name('logout');

// Authenticated Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/snippets/create', [SnippetController::class, 'create'])->name('snippets.create'); // Blade form
    Route::post('/snippets', [SnippetController::class, 'store'])->name('snippets.store'); // Handle form submission
    Route::get('/api/snippets', [SnippetController::class, 'apiIndex'])->name('api.snippets'); // JSON endpoint

    Route::post('/snippets/{snippet}/rate', [RatingController::class, 'rate'])->name('snippets.rate');
    Route::post('/snippets/{snippet}/suggest', [SuggestionController::class, 'store'])->name('snippets.suggest');
    Route::delete('/snippets/{snippet}', [SnippetController::class, 'destroy'])->name('snippets.destroy');
});

Route::middleware(['auth'])->get('/auth/user', function () {
    return response()->json(Auth::user());
})->name('auth.user');






