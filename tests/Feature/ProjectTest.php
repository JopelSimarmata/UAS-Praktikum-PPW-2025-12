<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test authenticated user can create project with valid data (Positive Test)
     */
    public function test_authenticated_user_can_create_project(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $projectData = [
            'name' => 'New Project',
            'description' => 'Project description',
            'status' => 'planning',
            'start_date' => '2025-01-01',
            'end_date' => '2025-12-31',
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/projects', $projectData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => ['id', 'name', 'description', 'status']
            ])
            ->assertJson([
                'success' => true,
                'message' => 'Project created successfully',
            ]);

        $this->assertDatabaseHas('projects', [
            'name' => 'New Project',
            'user_id' => $user->id,
        ]);
    }

    /**
     * Test project creation with missing required fields (Negative Test)
     */
    public function test_project_creation_fails_with_missing_name(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $projectData = [
            'description' => 'Project description',
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/projects', $projectData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    /**
     * Test project creation with invalid status (Negative Test)
     */
    public function test_project_creation_fails_with_invalid_status(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $projectData = [
            'name' => 'New Project',
            'status' => 'invalid_status',
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/projects', $projectData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['status']);
    }

    /**
     * Test project creation with end_date before start_date (Negative Test)
     */
    public function test_project_creation_fails_with_invalid_dates(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $projectData = [
            'name' => 'New Project',
            'start_date' => '2025-12-31',
            'end_date' => '2025-01-01',
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/projects', $projectData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['end_date']);
    }

    /**
     * Test unauthenticated user cannot create project (Negative Test)
     */
    public function test_unauthenticated_user_cannot_create_project(): void
    {
        $projectData = [
            'name' => 'New Project',
            'description' => 'Project description',
        ];

        $response = $this->postJson('/api/projects', $projectData);

        $response->assertStatus(401);
    }

    /**
     * Test authenticated user can get all their projects (Positive Test)
     */
    public function test_authenticated_user_can_get_all_projects(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        Project::factory()->count(3)->create(['user_id' => $user->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/projects');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'data' => [
                        '*' => ['id', 'name', 'description', 'status']
                    ]
                ]
            ]);
    }

    /**
     * Test authenticated user can get specific project (Positive Test)
     */
    public function test_authenticated_user_can_get_specific_project(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;
        $project = Project::factory()->create(['user_id' => $user->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson("/api/projects/{$project->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => ['id', 'name', 'description']
            ])
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $project->id,
                    'name' => $project->name,
                ]
            ]);
    }

    /**
     * Test user cannot access another user's project (Negative Test)
     */
    public function test_user_cannot_access_another_users_project(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $token = $user1->createToken('test-token')->plainTextToken;
        
        $project = Project::factory()->create(['user_id' => $user2->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson("/api/projects/{$project->id}");

        $response->assertStatus(404);
    }

    /**
     * Test authenticated user can update their project (Positive Test)
     */
    public function test_authenticated_user_can_update_project(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;
        $project = Project::factory()->create(['user_id' => $user->id]);

        $updateData = [
            'name' => 'Updated Project Name',
            'status' => 'in_progress',
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->putJson("/api/projects/{$project->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Project updated successfully',
            ]);

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'name' => 'Updated Project Name',
            'status' => 'in_progress',
        ]);
    }

    /**
     * Test user cannot update another user's project (Negative Test)
     */
    public function test_user_cannot_update_another_users_project(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $token = $user1->createToken('test-token')->plainTextToken;
        
        $project = Project::factory()->create(['user_id' => $user2->id]);

        $updateData = [
            'name' => 'Updated Project Name',
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->putJson("/api/projects/{$project->id}", $updateData);

        $response->assertStatus(404);
    }

    /**
     * Test authenticated user can delete their project (Positive Test)
     */
    public function test_authenticated_user_can_delete_project(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;
        $project = Project::factory()->create(['user_id' => $user->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->deleteJson("/api/projects/{$project->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Project deleted successfully',
            ]);

        $this->assertDatabaseMissing('projects', [
            'id' => $project->id,
        ]);
    }

    /**
     * Test user cannot delete another user's project (Negative Test)
     */
    public function test_user_cannot_delete_another_users_project(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $token = $user1->createToken('test-token')->plainTextToken;
        
        $project = Project::factory()->create(['user_id' => $user2->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->deleteJson("/api/projects/{$project->id}");

        $response->assertStatus(404);
        
        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
        ]);
    }

    /**
     * Test getting non-existent project returns 404 (Negative Test)
     */
    public function test_getting_non_existent_project_returns_404(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/projects/99999');

        $response->assertStatus(404);
    }
}
