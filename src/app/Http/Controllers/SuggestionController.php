<?php

namespace App\Http\Controllers;

use App\Models\Suggestion;
use App\Models\Snippet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SuggestionController extends Controller
{
    public function store(Request $request, Snippet $snippet)
    {
        $request->validate([
            'suggested_code' => 'required',
            'comment' => 'nullable|string',
        ]);

        try {
            $filePath = $snippet->file_path;

            $branchName = str_replace(
                ' ',
                '',
                Auth::user()->name . '-' . now()->format('Ymd') . '-' . str_replace(' ', '', $snippet->title)
            );
            $branchName = preg_replace('/[^a-zA-Z0-9\-]/', '', $branchName);

            $github = new \GuzzleHttp\Client();
            $baseBranch = 'master';
            $owner = config('services.github.owner');
            $repo = config('services.github.repo');
            $token = config('services.github.token');

            $response = $github->get("https://api.github.com/repos/$owner/$repo/git/ref/heads/$baseBranch", [
                'headers' => ['Authorization' => "token $token"],
            ]);
            $baseSha = json_decode($response->getBody(), true)['object']['sha'];

            $github->post("https://api.github.com/repos/$owner/$repo/git/refs", [
                'headers' => ['Authorization' => "token $token"],
                'json' => [
                    'ref' => "refs/heads/$branchName",
                    'sha' => $baseSha,
                ],
            ]);

            $response = $github->get("https://api.github.com/repos/$owner/$repo/contents/$filePath", [
                'headers' => ['Authorization' => "token $token"],
            ]);

            $fileData = json_decode($response->getBody(), true);
            $originalContent = base64_decode($fileData['content']);
            $fileSha = $fileData['sha'];

            $updatedContent = $this->mergeChanges($originalContent, $snippet->code, $request->suggested_code);

            $github->put("https://api.github.com/repos/$owner/$repo/contents/$filePath", [
                'headers' => ['Authorization' => "token $token"],
                'json' => [
                    'message' => "Suggested changes for {$snippet->title}",
                    'content' => base64_encode($updatedContent),
                    'sha' => $fileSha,
                    'branch' => $branchName,
                ],
            ]);

            Suggestion::create([
                'user_id' => Auth::id(),
                'snippet_id' => $snippet->id,
                'suggested_code' => $request->suggested_code,
                'comment' => $request->comment,
                'status' => 'pending',
                'branch_name' => $branchName,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Suggestion submitted successfully.',
                'pr_url' => "https://github.com/$owner/$repo/pull/$branchName",
            ]);
        } catch (\Exception $e) {
            Log::error('Error in suggestion submission', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json(['success' => false, 'error' => 'Failed to submit suggestion. Please try again.'], 500);
        }
    }

    private function mergeChanges($originalContent, $snippetCode, $suggestedCode)
    {
        return str_replace($snippetCode, $suggestedCode, $originalContent);
    }
}
