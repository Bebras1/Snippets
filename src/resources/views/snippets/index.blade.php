@extends('layouts.app')

@section('content')
    <h1>Your Snippets</h1>
    <a href="{{ route('snippets.create') }}">Create New Snippet</a>

    <ul>
        @foreach ($snippets as $snippet)
            <li>
                <a href="{{ route('snippets.show', $snippet) }}">{{ $snippet->title }}</a>
                <a href="{{ route('snippets.edit', $snippet) }}">Edit</a>
                <form action="{{ route('snippets.destroy', $snippet) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Delete</button>
                </form>
            </li>
        @endforeach
    </ul>
@endsection
