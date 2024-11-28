@extends('layouts.app')

@section('content')
    <h1 class="text-xl font-bold mb-4">Suggest a Change for {{ $snippet->title }}</h1>

    <form action="{{ route('snippets.suggest', $snippet) }}" method="POST" class="bg-gray-800 p-6 rounded">
        @csrf
        <div class="mb-4">
            <label for="language" class="block text-gray-300 mb-2">Language</label>
            <select id="language" name="language" onchange="updateCodeMirrorMode()"
                class="w-full p-2 bg-gray-700 text-gray-300 rounded">
                <option value="javascript" {{ $snippet->language == 'javascript' ? 'selected' : '' }}>JavaScript</option>
                <option value="php" {{ $snippet->language == 'php' ? 'selected' : '' }}>PHP</option>
                <option value="python" {{ $snippet->language == 'python' ? 'selected' : '' }}>Python</option>
                <option value="htmlmixed" {{ $snippet->language == 'htmlmixed' ? 'selected' : '' }}>HTML</option>
                <option value="css" {{ $snippet->language == 'css' ? 'selected' : '' }}>CSS</option>
            </select>
        </div>
        <div class="mb-4">
            <label for="suggested_code" class="block text-gray-300 mb-2">Suggested Code</label>
            <textarea name="suggested_code" id="suggested_code" required
                class="w-full bg-gray-700 text-gray-300 p-2 rounded">{{ $snippet->code }}</textarea>
        </div>
        <div class="mb-4">
            <label for="comment" class="block text-gray-300 mb-2">Comment (optional)</label>
            <textarea name="comment" id="comment"
                class="w-full bg-gray-700 text-gray-300 p-2 rounded" placeholder="Leave a comment..."></textarea>
        </div>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            Submit Suggestion
        </button>
    </form>
    @if(session('success'))
        <div class="bg-green-500 text-white p-4 rounded mt-4">{{ session('success') }}</div>
    @endif
@endsection

@section('scripts')
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
