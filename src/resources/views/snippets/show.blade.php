@extends('layouts.app')

@section('content')
    @if(session('success'))
        <div class="bg-green-500 text-white p-4 rounded mb-4">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="bg-red-500 text-white p-4 rounded mb-4">{{ session('error') }}</div>
    @endif

    <h1 class="text-xl font-bold mb-4">{{ $snippet->title }}</h1>

    <pre class="bg-gray-800 text-gray-300 p-4 rounded">{{ $snippet->code }}</pre>
    <p><strong>Language:</strong> {{ $snippet->language }}</p>
    <p>{{ $snippet->description }}</p>

    <h2 class="text-lg font-bold mt-8">Rate this Snippet</h2>
    <form action="{{ route('ratings.store', $snippet) }}" method="POST" class="mt-4">
        @csrf
        <select name="rating" id="rating" required class="p-2 rounded bg-gray-700 text-gray-300">
            @for ($i = 1; $i <= 5; $i++)
                <option value="{{ $i }}">{{ $i }}</option>
            @endfor
        </select>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            Submit Rating
        </button>
    </form>

    @if($snippet->averageRating())
        <p class="mt-4">Average Rating: {{ round($snippet->averageRating(), 2) }}</p>
    @endif

    <h2 class="text-lg font-bold mt-8">Suggestions</h2>
    <ul class="list-disc pl-5 space-y-4">
        @foreach($snippet->suggestions as $suggestion)
            <li class="bg-gray-800 p-4 rounded">
                <pre class="bg-gray-700 p-4 rounded">{{ $suggestion->suggested_code }}</pre>
                <p><strong>Comment:</strong> {{ $suggestion->comment }}</p>
                <p><strong>Status:</strong> {{ ucfirst($suggestion->status) }}</p>
            </li>
        @endforeach
    </ul>
@endsection
