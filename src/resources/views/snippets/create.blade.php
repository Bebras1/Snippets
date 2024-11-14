@extends('layouts.app')

@section('content')
    <h1>Create New Snippet</h1>

    <form action="{{ route('snippets.store') }}" method="POST">
        @csrf
        <input type="text" name="title" placeholder="Title" required>
        <textarea name="code" placeholder="Code" required></textarea>
        <input type="text" name="language" placeholder="Language" required>
        <textarea name="description" placeholder="Description"></textarea>
        <button type="submit">Save</button>
    </form>
@endsection
