<?php

namespace App\Http\Controllers;

use App\Enums\TaskStatus;
use App\Repositories\Interfaces\TaskRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class TaskController extends Controller
{
    protected $repository;

    public function __construct(TaskRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request): mixed
    {
       $filter = $request->query('filter');
       $data = $this->repository->all($filter);
        
       return Inertia::render('tasks/Index', [
            'tasks' => $data,
            'filters' => [
                'filter' => $request->get('filter', ''),
            ],
            'status' => TaskStatus::cases(),
        ]);  
    }


    public function store(Request $request): mixed
    {
        $data = $request->validate([
            'title' => 'required|string|unique:tasks,title',
            'description' => 'nullable',
            'status' => 'required|in:' . 
                implode(
                    ',', 
                    collect(TaskStatus::cases()
                )->pluck('value')->all()),
        ]);

        $data['user_id'] = Auth::id();

        $this->repository->store($data);
        return redirect()->route('tasks.index')->with('success', 'Task created.');
    }

    public function show($id)
    {
      $task = $this->repository->find($id);
      abort_if(!$task, 404);
      return Inertia::render('Tasks/Index', ['task' => $task]);
    }

    public function update(Request $request, $id)
    {
        $task = $this->repository->find($id);
        abort_if(!$task, 404);

        $data = $request->validate([
            'title' => 'required|string|unique:tasks,title,' . $task->id,
            'description' => 'nullable',
            'status' => 'required|in:Incomplete,Complete',
        ]);

        $this->repository->update($id, $data);

        return redirect()->route('tasks.index')->with('success', 'Task updated.');
    }

    public function destroy($id)
    {
        $task = $this->repository->find($id);
        abort_if(!$task, 404);

        $this->repository->delete($id);

        return redirect()->route('tasks.index')->with('success', 'Task deleted.');
    }
}
