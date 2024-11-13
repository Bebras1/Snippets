<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Snippet;
use Illuminate\Support\Facades\Auth;

class SnippetController extends Controller
{
    public function index()
    {
        $snippets = Snippet::where('user_id', Auth::id())->get();
        return view('snippets.index', compact('snippets'));
    }

    public function create()
    {
        return view('snippets.create');
    }

    public function store(Request $request)
    {
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
        $this->authorize('view', $snippet);
        return view('snippets.show', compact('snippet'));
    }

    public function edit(Snippet $snippet)
    {
        $this->authorize('update', $snippet);
        return view('snippets.edit', compact('snippet'));
    }

    public function update(Request $request, Snippet $snippet)
    {
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
        $this->authorize('delete', $snippet);
        $snippet->delete();

        return redirect()->route('snippets.index')->with('success', 'Snippet deleted successfully.');
    }

}
