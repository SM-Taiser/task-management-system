<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\TaskRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    protected $repository;

    public function __construct(TaskRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function sendResponse($result, $code = 200, $message = 'Request processed successfully'): JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $message,
            'data'    => $result,
        ];
  
        return response()->json($response, $code);
    }
  
    public function sendError($error, $errorMessages = [], $code = 404): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];
  
        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }
  
        return response()->json($response, $code);
    }

    public function index(Request $request): mixed
    {
       $filter = $request->query('filter');
       $data = $this->repository->all($filter);
        
       return $this->sendResponse($data);
    }
 
    public function store(Request $request): mixed
    {
        $data = $request->validate([
            'title' => 'required|string|unique:tasks,title',
            'description' => 'nullable',
            'status' => 'required|in:Incomplete,Complete',
        ]);

        $data['user_id'] = Auth::id();

        $response = $this->repository->store($data);

        return $this->sendResponse($response, 201, 'Task created successfully');
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $task = $this->repository->find($id);
        abort_if(!$task, 404);

        $data = $request->validate([
            'title' => 'required|string|unique:tasks,title,' . $task->id,
            'description' => 'nullable',
            'status' => 'required|in:Incomplete,Complete',
        ]);

        $response = $this->repository->update($id, $data);

        return $this->sendResponse($response, 200, 'Task updated successfully');
    }

    public function destroy(int $id): JsonResponse
    {
        $task = $this->repository->find($id);
        abort_if(!$task, 404);

        $response = $this->repository->delete($id);

        return $this->sendResponse($response, 200, 'Task deleted successfully');
    }
}
