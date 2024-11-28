@extends('layouts.app')

@section('content')
    <div id="home"></div>
@endsection

@section('scripts')
    @viteReactRefresh
    @vite('resources/js/reactApp.jsx')
@endsection
