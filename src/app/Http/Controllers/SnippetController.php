<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Snippet;
use Illuminate\Support\Facades\Auth;

class SnippetController extends Controller
{
    public function index()
    {
        // List all snippets
        $snippets = Snippet::where('user_id', Auth::id())->get();
        return view('snippets.index', compact('snippets'));
    }

    public function create()
    {
        // Show form to create a new snippet
        return view('snippets.create');
    }

    public function store(Request $request)
    {
        // Validate and store the new snippet
        $request->validate([
            'title' => 'required|string|max:255',
            'code' => 'required',
            'language' => 'required|string|max:100',
            'description' => 'nullable|string',
        ]);

        Snippet::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'code' => $request->code,
            'language' => $request->language,
            'description' => $request->description,
        ]);

        return redirect()->route('snippets.index')->with('success', 'Snippet created successfully.');
    }

    public function show(Snippet $snippet)
    {
        // Show a single snippet
        $this->authorize('view', $snippet); // Ensure the user has permission to view
        return view('snippets.show', compact('snippet'));
    }

    public function edit(Snippet $snippet)
    {
        // Show form to edit an existing snippet
        $this->authorize('update', $snippet);
        return view('snippets.edit', compact('snippet'));
    }

    public function update(Request $request, Snippet $snippet)
    {
        // Validate and update the snippet
        $this->authorize('update', $snippet);

        $request->validate([
            'title' => 'required|string|max:255',
            'code' => 'required',
            'language' => 'required|string|max:100',
            'description' => 'nullable|string',
        ]);

        $snippet->update($request->only('title', 'code', 'language', 'description'));

        return redirect()->route('snippets.index')->with('success', 'Snippet updated successfully.');
    }

    public function destroy(Snippet $snippet)
    {
        // Delete a snippet
        $this->authorize('delete', $snippet);
        $snippet->delete();

        return redirect()->route('snippets.index')->with('success', 'Snippet deleted successfully.');
    }

}
