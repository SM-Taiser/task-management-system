<?php

namespace App\Repositories;

use App\Enums\TaskStatus;
use App\Jobs\SendTaskNotificationJob;
use App\Models\Task;
use App\Repositories\Interfaces\TaskRepositoryInterface;
use Illuminate\Support\Facades\Gate;

class TaskRepository implements TaskRepositoryInterface
{
    protected $model;

    public function __construct(Task $model)
    {
        $this->model = $model;
    }

    public function all(string $filter = null)
    {
        $query = $this->model->query();

        if (!empty($filter)) {
           return $query->with('user.role')->where('status', $filter)->latest()->get();
        } 

        return $this->model->with('user.role')->latest()->get();
    }

    public function find(int $id): Task
    {
        return $this->model->findOrFail($id);
    }

    public function store(array $data): Task
    {
        Gate::authorize('create', $this->model);

        $data = $this->model->create($data);
        // Queue email for new task
        SendTaskNotificationJob::dispatch($data, 'created');

        return $data;
    }

    public function update(int $id, array $data): bool
    {
        $task = $this->find($id);
        Gate::authorize('update', $task);

        if (!empty($data['status']) && $data['status'] === TaskStatus::COMPLETED->value) {
          // Queue email when task is completed
          SendTaskNotificationJob::dispatch($task, 'completed');
        }

        return $task->update($data);
    }

    public function delete(int $id): bool
    {
        $task = $this->find($id);
        Gate::authorize('delete', $task);

        return $task->delete();
    }
}
