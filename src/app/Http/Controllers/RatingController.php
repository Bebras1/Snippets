<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\Snippet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    public function rate(Request $request, Snippet $snippet)
    {
        $request->validate(['rating' => 'required|integer|min:1|max:5']);

        $user = Auth::user();
        $snippet->ratings()->updateOrCreate(
            ['user_id' => $user->id],
            ['rating' => $request->rating]
        );

        $averageRating = $snippet->ratings()->avg('rating') ?? 0;
        $totalRatings = $snippet->ratings()->count();

        $snippet->update(['average_rating' => $averageRating]);

        return response()->json([
            'success' => true,
            'message' => 'Rating updated successfully.',
            'average_rating' => round($averageRating, 1),
            'total_ratings' => $totalRatings,
            'user_rating' => $request->rating,
        ]);
    }
}
