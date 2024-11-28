<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Snippet;

class SnippetController extends Controller
{
    public function index()
    {
        $snippets = Snippet::with('ratings')->get();
        return view('home', compact('snippets'));
    }

    public function apiIndex()
    {
        $snippets = Snippet::with('ratings')->get()->map(function ($snippet) {
            return [
                'id' => $snippet->id,
                'title' => $snippet->title,
                'language' => $snippet->language,
                'code' => $snippet->code,
                'averageRating' => $snippet->ratings()->avg('rating') ?? 0,
                'totalRatings' => $snippet->ratings()->count(),
            ];
        });

        return response()->json($snippets);
    }

    public function create()
    {
        return view('snippets.create');
    }

    public function store(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'code' => 'required',
            'language' => 'required|string|max:100',
            'description' => 'nullable|string',
            'file_path' => 'required|string',
        ]);

        Snippet::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'code' => $request->code,
            'language' => $request->language,
            'description' => $request->description,
            'file_path' => $request->file_path,
        ]);

        return response()->noContent();
    }

    public function destroy(Snippet $snippet)
    {
        $snippet->delete();
        return response()->noContent();
    }
}
