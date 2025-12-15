<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test authenticated user can create task in their project (Positive Test)
     */
    public function test_authenticated_user_can_create_task(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;
        $project = Project::factory()->create(['user_id' => $user->id]);

        $taskData = [
            'title' => 'New Task',
            'description' => 'Task description',
            'status' => 'pending',
            'priority' => 'high',
            'due_date' => '2025-12-31',
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson("/api/projects/{$project->id}/tasks", $taskData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => ['id', 'title', 'description', 'status', 'priority']
            ])
            ->assertJson([
                'success' => true,
                'message' => 'Task created successfully',
            ]);

        $this->assertDatabaseHas('tasks', [
            'title' => 'New Task',
            'project_id' => $project->id,
        ]);
    }

    /**
     * Test task creation with missing required fields (Negative Test)
     */
    public function test_task_creation_fails_with_missing_title(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;
        $project = Project::factory()->create(['user_id' => $user->id]);

        $taskData = [
            'description' => 'Task description',
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson("/api/projects/{$project->id}/tasks", $taskData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title']);
    }

    /**
     * Test task creation with invalid status (Negative Test)
     */
    public function test_task_creation_fails_with_invalid_status(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;
        $project = Project::factory()->create(['user_id' => $user->id]);

        $taskData = [
            'title' => 'New Task',
            'status' => 'invalid_status',
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson("/api/projects/{$project->id}/tasks", $taskData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['status']);
    }

    /**
     * Test task creation with invalid priority (Negative Test)
     */
    public function test_task_creation_fails_with_invalid_priority(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;
        $project = Project::factory()->create(['user_id' => $user->id]);

        $taskData = [
            'title' => 'New Task',
            'priority' => 'invalid_priority',
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson("/api/projects/{$project->id}/tasks", $taskData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['priority']);
    }

    /**
     * Test user cannot create task in another user's project (Negative Test)
     */
    public function test_user_cannot_create_task_in_another_users_project(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $token = $user1->createToken('test-token')->plainTextToken;
        $project = Project::factory()->create(['user_id' => $user2->id]);

        $taskData = [
            'title' => 'New Task',
            'description' => 'Task description',
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson("/api/projects/{$project->id}/tasks", $taskData);

        $response->assertStatus(404);
    }

    /**
     * Test authenticated user can get all tasks in their project (Positive Test)
     */
    public function test_authenticated_user_can_get_all_tasks(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;
        $project = Project::factory()->create(['user_id' => $user->id]);
        
        Task::factory()->count(3)->create(['project_id' => $project->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson("/api/projects/{$project->id}/tasks");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'data' => [
                        '*' => ['id', 'title', 'description', 'status', 'priority']
                    ]
                ]
            ]);
    }

    /**
     * Test authenticated user can get specific task (Positive Test)
     */
    public function test_authenticated_user_can_get_specific_task(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;
        $project = Project::factory()->create(['user_id' => $user->id]);
        $task = Task::factory()->create(['project_id' => $project->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson("/api/projects/{$project->id}/tasks/{$task->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => ['id', 'title', 'description']
            ])
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $task->id,
                    'title' => $task->title,
                ]
            ]);
    }

    /**
     * Test user cannot access task from another user's project (Negative Test)
     */
    public function test_user_cannot_access_task_from_another_users_project(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $token = $user1->createToken('test-token')->plainTextToken;
        
        $project = Project::factory()->create(['user_id' => $user2->id]);
        $task = Task::factory()->create(['project_id' => $project->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson("/api/projects/{$project->id}/tasks/{$task->id}");

        $response->assertStatus(404);
    }

    /**
     * Test authenticated user can update task (Positive Test)
     */
    public function test_authenticated_user_can_update_task(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;
        $project = Project::factory()->create(['user_id' => $user->id]);
        $task = Task::factory()->create(['project_id' => $project->id]);

        $updateData = [
            'title' => 'Updated Task Title',
            'status' => 'in_progress',
            'priority' => 'high',
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->putJson("/api/projects/{$project->id}/tasks/{$task->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Task updated successfully',
            ]);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Updated Task Title',
            'status' => 'in_progress',
            'priority' => 'high',
        ]);
    }

    /**
     * Test user cannot update task in another user's project (Negative Test)
     */
    public function test_user_cannot_update_task_in_another_users_project(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $token = $user1->createToken('test-token')->plainTextToken;
        
        $project = Project::factory()->create(['user_id' => $user2->id]);
        $task = Task::factory()->create(['project_id' => $project->id]);

        $updateData = [
            'title' => 'Updated Task Title',
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->putJson("/api/projects/{$project->id}/tasks/{$task->id}", $updateData);

        $response->assertStatus(404);
    }

    /**
     * Test authenticated user can delete task (Positive Test)
     */
    public function test_authenticated_user_can_delete_task(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;
        $project = Project::factory()->create(['user_id' => $user->id]);
        $task = Task::factory()->create(['project_id' => $project->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->deleteJson("/api/projects/{$project->id}/tasks/{$task->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Task deleted successfully',
            ]);

        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id,
        ]);
    }

    /**
     * Test user cannot delete task in another user's project (Negative Test)
     */
    public function test_user_cannot_delete_task_in_another_users_project(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $token = $user1->createToken('test-token')->plainTextToken;
        
        $project = Project::factory()->create(['user_id' => $user2->id]);
        $task = Task::factory()->create(['project_id' => $project->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->deleteJson("/api/projects/{$project->id}/tasks/{$task->id}");

        $response->assertStatus(404);
        
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
        ]);
    }

    /**
     * Test cascade delete - tasks deleted when project is deleted (Positive Test)
     */
    public function test_tasks_are_deleted_when_project_is_deleted(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;
        $project = Project::factory()->create(['user_id' => $user->id]);
        $task = Task::factory()->create(['project_id' => $project->id]);

        $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->deleteJson("/api/projects/{$project->id}");

        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    /**
     * Test getting non-existent task returns 404 (Negative Test)
     */
    public function test_getting_non_existent_task_returns_404(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;
        $project = Project::factory()->create(['user_id' => $user->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson("/api/projects/{$project->id}/tasks/99999");

        $response->assertStatus(404);
    }
}
