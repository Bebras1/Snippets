<!DOCTYPE html>
<html lang="en" class="bg-gray-900 text-gray-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Code Snippets')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col">
    <header class="bg-gray-800 py-4 shadow-lg">
        <div class="container mx-auto flex justify-between items-center px-6">
            <h1 class="text-xl font-bold"><a href="/">Code Snippets</a></h1>
            <nav class="flex space-x-4">
                @auth
                    <a href="/snippets/create" class="text-gray-300 hover:text-white">Add Snippet</a>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-red-500 hover:text-red-400">Logout</button>
                    </form>
                @else
                    <a href="{{ route('github.login') }}" class="text-gray-300 hover:text-white">Login with GitHub</a>
                @endauth
            </nav>
        </div>
    </header>

    <main class="flex-grow container mx-auto py-8 px-6">
        @yield('content')
    </main>

    <footer class="bg-gray-800 py-4 text-center text-gray-500">
        &copy; 2024 Code Snippets. All rights reserved.
    </footer>

    @yield('scripts')
    <script>
        window.__USER__ = @json(auth()->user());
        window.isAuthenticated = {{ Auth::check() ? 'true' : 'false' }};
        window.isAdmin = {{ Auth::user() && Auth::user()->is_admin ? 'true' : 'false' }};
    </script>
</body>
</html>
