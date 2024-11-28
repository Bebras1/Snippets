<?php

namespace App\Policies;

use App\Models\Snippet;
use App\Models\User;

class SnippetPolicy
{
    /**
     * Determine whether the user can view the snippet.
     */
    public function view(User $user, Snippet $snippet): bool
    {
        return $user->id === $snippet->user_id;
    }

    /**
     * Determine whether the user can update the snippet.
     */
    public function update(User $user, Snippet $snippet): bool
    {
        return $user->id === $snippet->user_id;
    }

    /**
     * Determine whether the user can delete the snippet.
     */
    public function delete(User $user, Snippet $snippet): bool
    {
        return $user->id === $snippet->user_id;
    }
}
