@extends('layouts.app')

@section('content')
    <h1 class="text-xl font-bold mb-4">Edit Snippet</h1>

    <form action="{{ route('snippets.update', $snippet) }}" method="POST" class="bg-gray-800 p-4 rounded">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="title" class="block text-gray-300">Title</label>
            <input type="text" id="title" name="title" value="{{ old('title', $snippet->title) }}" required
                class="w-full p-2 rounded bg-gray-700 text-gray-300">
            @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label for="code" class="block text-gray-300">Code</label>
            <textarea id="code" name="code" required
                class="w-full p-2 rounded bg-gray-700 text-gray-300">{{ old('code', $snippet->code) }}</textarea>
            @error('code') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label for="language" class="block text-gray-300">Language</label>
            <input type="text" id="language" name="language" value="{{ old('language', $snippet->language) }}" required
                class="w-full p-2 rounded bg-gray-700 text-gray-300">
            @error('language') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label for="description" class="block text-gray-300">Description</label>
            <textarea id="description" name="description"
                class="w-full p-2 rounded bg-gray-700 text-gray-300">{{ old('description', $snippet->description) }}</textarea>
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            Update
        </button>
    </form>
@endsection
