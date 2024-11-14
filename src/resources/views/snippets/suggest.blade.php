@extends('layouts.app')

@section('content')
    <h1>Suggest a Change for {{ $snippet->title }}</h1>

    <form action="{{ route('snippets.suggest', $snippet) }}" method="POST">
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

    @if(session('success'))
        <p>{{ session('success') }}</p>
    @endif
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
