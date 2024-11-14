@extends('layouts.app')

@section('content')
    <h1>Edit Snippet</h1>

    <form action="{{ route('snippets.update', $snippet) }}" method="POST">
        @csrf
        @method('PUT')
        <input type="text" name="title" value="{{ $snippet->title }}" required>
        <textarea name="code" required>{{ $snippet->code }}</textarea>
        <input type="text" name="language" value="{{ $snippet->language }}" required>
        <textarea name="description">{{ $snippet->description }}</textarea>
        <button type="submit">Update</button>
    </form>
@endsection
