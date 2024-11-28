@extends('layouts.app')

@section('content')
    <h1 class="text-xl font-bold mb-4">Your Snippets</h1>

    <a href="{{ route('snippets.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 mb-4 inline-block">
        Create New Snippet
    </a>

    <ul class="list-disc pl-5 space-y-4">
        @foreach ($snippets as $snippet)
            <li class="bg-gray-800 p-4 rounded">
                <a href="{{ route('snippets.show', $snippet) }}" class="text-blue-400 hover:underline">
                    {{ $snippet->title }}
                </a>
                <div class="flex space-x-4 mt-2">
                    <a href="{{ route('snippets.edit', $snippet) }}" class="text-yellow-400 hover:underline">Edit</a>
                    <form action="{{ route('snippets.destroy', $snippet) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:underline">Delete</button>
                    </form>
                </div>
            </li>
        @endforeach
    </ul>
@endsection
