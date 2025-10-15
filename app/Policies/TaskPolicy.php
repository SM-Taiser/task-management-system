<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    /**
     * Determine whether the user can view any tasks.
     */
    public function viewAny(User $user): bool
    {
        // Any authenticated user can view tasks
        return isset($user->id);
    }

    /**
     * Determine whether the user can view the task.
     */
    public function view(User $user, Task $task): bool
    {
        // All authenticated users can view any task
        return true;
    }

    /**
     * Determine whether the user can create tasks.
     */
    public function create(User $user): bool
    {
        return isset($user->id);
    }

    /**
     * Determine whether the user can update the task.
     */
    public function update(User $user, Task $task): bool
    {
        // Allow if admin or owner
        return $user->isAdmin() || $task->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the task.
     */
    public function delete(User $user, Task $task): bool
    {
        return $user->isAdmin() || $task->user_id === $user->id;
    }

}
