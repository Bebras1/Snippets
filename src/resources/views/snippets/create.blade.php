@extends('layouts.app')

@section('content')
    <div id="create-snippet"></div>
@endsection

@section('scripts')
    @viteReactRefresh
    @vite('resources/js/reactApp.jsx')
@endsection
