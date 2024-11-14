@extends('layouts.app')

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

@section('content')
    <h1>{{ $snippet->title }}</h1>
    <pre style="background-color: #f7f7f7; padding: 10px; border-radius: 5px;">{{ $snippet->code }}</pre>
    <p><strong>Language:</strong> {{ $snippet->language }}</p>
    <p>{{ $snippet->description }}</p>

    <h2>Rate this Snippet</h2>
    <form action="{{ route('ratings.store', $snippet) }}" method="POST">
        @csrf
        <label for="rating">Rate this snippet:</label>
        <select name="rating" id="rating" required>
            @for ($i = 1; $i <= 5; $i++)
                <option value="{{ $i }}">{{ $i }}</option>
            @endfor
        </select>
        <button type="submit">Submit Rating</button>
    </form>

    @if($snippet->averageRating())
        <p>Average Rating: {{ round($snippet->averageRating(), 2) }}</p>
    @endif

    <h2>Suggest Changes</h2>
    <form action="{{ route('suggestions.store', $snippet) }}" method="POST">
        @csrf
        <label for="language">Language:</label>
        <select id="language" name="language" onchange="updateCodeMirrorMode()">
            <option value="javascript" {{ $snippet->language == 'javascript' ? 'selected' : '' }}>JavaScript</option>
            <option value="php" {{ $snippet->language == 'php' ? 'selected' : '' }}>PHP</option>
            <option value="python" {{ $snippet->language == 'python' ? 'selected' : '' }}>Python</option>
            <option value="htmlmixed" {{ $snippet->language == 'htmlmixed' ? 'selected' : '' }}>HTML</option>
            <option value="css" {{ $snippet->language == 'css' ? 'selected' : '' }}>CSS</option>
        </select>

        <label for="suggested_code">Suggested Code:</label>
        <textarea name="suggested_code" id="suggested_code" required>{{ $snippet->code }}</textarea>

        <label for="comment">Comment (optional):</label>
        <textarea name="comment" id="comment" style="width: 100%; height: 60px;"></textarea>

        <button type="submit">Submit Suggestion</button>
    </form>

    <h2>Suggestions</h2>
    <ul>
    @foreach($snippet->suggestions as $suggestion)
        <li style="margin-bottom: 20px;">
            <pre style="background-color: #f7f7f7; padding: 10px; border-radius: 5px;">{{ $suggestion->suggested_code }}</pre>
            <p><strong>Comment:</strong> {{ $suggestion->comment }}</p>
            <p><strong>Status:</strong> {{ ucfirst($suggestion->status) }}</p>

            @if(Auth::id() === $snippet->user_id || Auth::user()->isAdmin())
                <form action="{{ route('suggestions.approve', $suggestion) }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-success">Approve</button>
                </form>
                <form action="{{ route('suggestions.reject', $suggestion) }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-danger">Reject</button>
                </form>
            @endif
        </li>
    @endforeach
</ul>
@endsection

@section('scripts')
    <!-- CodeMirror Assets -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/codemirror.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/theme/monokai.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/codemirror.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/mode/javascript/javascript.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/mode/php/php.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/mode/python/python.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/mode/htmlmixed/htmlmixed.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/mode/css/css.min.js"></script>

    <script>
        let codeMirrorEditor;

        document.addEventListener("DOMContentLoaded", function() {
            codeMirrorEditor = CodeMirror.fromTextArea(document.getElementById("suggested_code"), {
                lineNumbers: true,
                mode: document.getElementById("language").value,
                theme: "monokai",
                indentUnit: 4,
                lineWrapping: true,
            });
        });

        function updateCodeMirrorMode() {
            const selectedLanguage = document.getElementById("language").value;
            codeMirrorEditor.setOption("mode", selectedLanguage);
        }
    </script>
@endsection
