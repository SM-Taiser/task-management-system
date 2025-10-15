<?php

namespace Tests\Unit\Repositories;

use App\Enums\TaskStatus;
use App\Models\Task;
use App\Models\User;
use App\Models\Role;
use App\Repositories\TaskRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Log;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    protected TaskRepository $repository;
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->repository = new TaskRepository(new Task());
        
        // Create a user with role for testing
        $role = Role::factory()->create(['name' => 'Admin']);
        $this->user = User::factory()->create(['role_id' => $role->id]);
        
        $this->actingAs($this->user);
    }

    #[Test]
    public function it_can_get_all_tasks_without_filter(): void
    {
        Task::factory()->count(3)->create([
            'user_id' => $this->user->id,
            'status' => TaskStatus::INCOMPLETE
        ]);

        // Act
        $tasks = $this->repository->all();

        // Assert
        $this->assertCount(3, $tasks);
        $this->assertTrue($tasks->first()->relationLoaded('user'));
        $this->assertTrue($tasks->first()->user->relationLoaded('role'));
    }

    #[Test]
    public function it_can_get_tasks_with_status_filter(): void
    {
        Task::factory()->count(2)->create([
            'user_id' => $this->user->id,
            'status' => TaskStatus::INCOMPLETE->value
        ]);
        Task::factory()->create([
            'user_id' => $this->user->id,
            'status' => TaskStatus::COMPLETED->value
        ]);

        // Act
        $tasks = $this->repository->all(TaskStatus::INCOMPLETE->value);

        // Assert
        $this->assertCount(2, $tasks);
        $this->assertEquals(TaskStatus::INCOMPLETE->value, $tasks->first()->status);
    }

    #[Test]
    public function it_returns_tasks_in_latest_order(): void
    {
        $oldTask = Task::factory()->create([
            'user_id' => $this->user->id,
            'status' => TaskStatus::INCOMPLETE->value,
            'created_at' => now()->subDays(2)
        ]);
        $newTask = Task::factory()->create([
            'user_id' => $this->user->id,
            'status' => TaskStatus::COMPLETED->value,
            'created_at' => now()
        ]);

        // Act
        $tasks = $this->repository->all();

        // Assert
        $this->assertEquals($newTask->id, $tasks->first()->id);
        $this->assertEquals($oldTask->id, $tasks->last()->id);
    }

    #[Test]
    public function it_can_find_a_task_by_id(): void
    {
        $task = Task::factory()->create([
            'user_id' => $this->user->id,
            'status' => TaskStatus::INCOMPLETE->value
        ]);

        // Act
        $foundTask = $this->repository->find($task->id);

        // Assert
        $this->assertInstanceOf(Task::class, $foundTask);
        $this->assertEquals($task->id, $foundTask->id);
    }

    #[Test]
    public function it_throws_exception_when_task_not_found(): void
    {
        // Assert
        $this->expectException(ModelNotFoundException::class);

        // Act
        $this->repository->find(999);
    }

    #[Test]
    public function it_can_store_a_new_task(): void
    {
        Gate::shouldReceive('authorize')
            ->once()
            ->with('create', \Mockery::type(Task::class))
            ->andReturn(true);

        $data = [
            'title' => 'Test Task',
            'description' => 'Test Description',
            'status' => TaskStatus::INCOMPLETE->value,
            'user_id' => $this->user->id
        ];

        // Act
        $task = $this->repository->store($data);

        // Assert
        $this->assertInstanceOf(Task::class, $task);
        $this->assertEquals('Test Task', $task->title);
        $this->assertDatabaseHas('tasks', ['title' => 'Test Task']);
    }

    #[Test]
    public function it_checks_authorization_before_storing(): void
    {
        Gate::shouldReceive('authorize')
            ->once()
            ->with('create', \Mockery::type(Task::class))
            ->andThrow(new \Illuminate\Auth\Access\AuthorizationException());

        $data = [
            'title' => 'Test Task',
            'description' => 'Test Description',
            'status' => TaskStatus::INCOMPLETE->value,
            'user_id' => $this->user->id
        ];

        // Assert
        $this->expectException(\Illuminate\Auth\Access\AuthorizationException::class);

        // Act
        $this->repository->store($data);
    }

    #[Test]
    public function it_can_update_a_task(): void
    {
        $task = Task::factory()->create([
            'user_id' => $this->user->id,
            'title' => 'Old Title',
            'status' => TaskStatus::INCOMPLETE->value
        ]);

        Gate::shouldReceive('authorize')
            ->once()
            ->with('update', \Mockery::type(Task::class))
            ->andReturn(true);

        $data = ['title' => 'New Title'];

        // Act
        $result = $this->repository->update($task->id, $data);

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'New Title'
        ]);
    }

    #[Test]
    public function it_checks_authorization_before_updating(): void
    {
        $task = Task::factory()->create([
            'user_id' => $this->user->id,
            'status' => TaskStatus::INCOMPLETE->value
        ]);

        Gate::shouldReceive('authorize')
            ->once()
            ->with('update', \Mockery::type(Task::class))
            ->andThrow(new \Illuminate\Auth\Access\AuthorizationException());

        // Assert
        $this->expectException(\Illuminate\Auth\Access\AuthorizationException::class);

        // Act
        $this->repository->update($task->id, ['title' => 'New Title']);
    }

    #[Test]
    public function it_can_delete_a_task(): void
    {
        $task = Task::factory()->create([
            'user_id' => $this->user->id,
            'status' => TaskStatus::INCOMPLETE->value
        ]);

        Gate::shouldReceive('authorize')
            ->once()
            ->with('delete', \Mockery::type(Task::class))
            ->andReturn(true);

        // Act
        $result = $this->repository->delete($task->id);

        // Assert
        $this->assertTrue($result);
        $this->assertSoftDeleted('tasks', ['id' => $task->id]);
    }

    #[Test]
    public function delete_throws_exception_when_task_not_found(): void
    {
        $this->withoutExceptionHandling();
        
        // Expect the exception to be thrown
        $this->expectException(ModelNotFoundException::class);

        // Act - This should throw ModelNotFoundException from find() method
        $this->repository->delete(999);
    }
}
