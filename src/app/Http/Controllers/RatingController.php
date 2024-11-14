<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\Snippet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    public function store(Request $request, Snippet $snippet)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $existingRating = Rating::where('user_id', Auth::id())
            ->where('snippet_id', $snippet->id)
            ->first();

        if ($existingRating) {
            $existingRating->update(['rating' => $request->rating]);
        } else {
            Rating::create([
                'user_id' => Auth::id(),
                'snippet_id' => $snippet->id,
                'rating' => $request->rating,
            ]);
        }

        return back()->with('success', 'Rating submitted successfully.');
    }
}

