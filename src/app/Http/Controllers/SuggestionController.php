<?php

namespace App\Http\Controllers;

use App\Models\Suggestion;
use App\Models\Snippet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuggestionController extends Controller
{
    public function store(Request $request, Snippet $snippet)
    {
        $request->validate([
            'suggested_code' => 'required',
            'comment' => 'nullable|string',
        ]);

        Suggestion::create([
            'user_id' => Auth::id(),
            'snippet_id' => $snippet->id,
            'suggested_code' => $request->suggested_code,
            'comment' => $request->comment,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Suggestion submitted successfully.');
    }

    public function approve(Suggestion $suggestion)
    {
        if (Auth::user()->isAdmin()) {
            $suggestion->update(['status' => 'approved']);
            return back()->with('success', 'Suggestion approved.');
        }

        return back()->with('error', 'Unauthorized action.');
    }

    public function reject(Suggestion $suggestion)
    {
        if (Auth::user()->isAdmin()) {
            $suggestion->update(['status' => 'rejected']);
            return back()->with('success', 'Suggestion rejected.');
        }

        return back()->with('error', 'Unauthorized action.');
    }
}
