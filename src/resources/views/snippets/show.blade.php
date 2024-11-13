@extends('layouts.app')

@section('content')
    <h1>{{ $snippet->title }}</h1>
    <pre>{{ $snippet->code }}</pre>
    <p><strong>Language:</strong> {{ $snippet->language }}</p>
    <p>{{ $snippet->description }}</p>
@endsection
