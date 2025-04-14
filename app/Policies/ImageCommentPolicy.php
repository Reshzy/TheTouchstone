<?php

namespace App\Policies;

use App\Models\ImageComment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ImageCommentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Anyone can view image comments
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ImageComment $imageComment): bool
    {
        // Anyone can view an image comment
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Any authenticated user can create an image comment
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ImageComment $imageComment): bool
    {
        // Users can only edit their own comments or admins can edit any comment
        return $user->id === $imageComment->user_id || $user->is_admin;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ImageComment $imageComment): bool
    {
        // Users can only delete their own comments or admins can delete any comment
        return $user->id === $imageComment->user_id || $user->is_admin;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ImageComment $imageComment): bool
    {
        // Only admins can restore comments
        return $user->is_admin;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ImageComment $imageComment): bool
    {
        // Only admins can force delete comments
        return $user->is_admin;
    }
}
