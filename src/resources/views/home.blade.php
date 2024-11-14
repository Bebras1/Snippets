@extends('layouts.app')

@section('title', 'Welcome to Code Snippets')

@section('content')
    <h1>Welcome to Code Snippets</h1>
    <p>Explore, rate, and suggest changes to code snippets.</p>
    <hr class="my-4">

    <div>
        <a class="btn btn-primary btn-lg" href="{{ route('snippets.index') }}" role="button">Browse Snippets</a>

        @auth
            <a class="btn btn-success btn-lg" href="{{ route('snippets.create') }}" role="button">Add Snippet</a>
        @else
            <a class="btn btn-info btn-lg" href="{{ route('github.login') }}" role="button">Login with GitHub to Suggest Changes</a>
        @endauth
    </div>
@endsection
