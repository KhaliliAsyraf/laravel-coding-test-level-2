<?php

namespace Tests\Unit;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Faker\Factory;

class UnitTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
    }

    public function testCreateUser()
    {
        $admin = User::factory()->create();
        $admin->assignRole(config('staticdata.roles.admin'));

        Sanctum::actingAs(
            $admin,
            ['*']
        );

        $payload = [
            'name' => 'userTest',
            'email' => $this->faker->unique()->safeEmail()
        ];

        $expectedResponse = [
            'success' =>  true,
            'message' => 'Created user successfully'
        ];

        $response = $this->post(route('api.create.user'), $payload);
        $response->assertJson($expectedResponse);
        $response->assertStatus(200);

        $this->assertDatabaseHas(
            'users',
            [
                'name' => $payload['name'],
                'email' => $payload['email']
            ]
        );
    }

    public function testCreateProjectAndAssignUser()
    {
        $productOwner = User::factory()->create();
        $productOwner->assignRole(config('staticdata.roles.product_owner'));

        Sanctum::actingAs(
            $productOwner,
            ['*']
        );

        $payload = [
            'name' => $this->faker->name()
        ];

        $expectedResponse = [
            'success' =>  true,
            'message' => 'Created project successfully'
        ];

        $response = $this->post(route('api.create.project'), $payload);
        $response->assertJson($expectedResponse);
        $response->assertStatus(200);

        $this->assertDatabaseHas(
            'projects',
            [
                'name' => $payload['name']
            ]
        );

        $project = Project::where('name', $payload['name'])->first();

        $users = User::factory(2)->create();

        $payload = [
            'title' => $this->faker->word(),
            'description' => $this->faker->text(50),
            'project_id' => $project->id
        ];

        foreach ($users as $user) {
            $payload['user_id'] = $user->id;

            $expectedResponse = [
                'success' =>  true,
                'message' => 'Created task successfully'
            ];

            $response = $this->post(route('api.create.task'), $payload);
            $response->assertJson($expectedResponse);
            $response->assertStatus(200);
        }

        $tasks = Task::orderBy('created_at', 'desc')->limit(2)->get();
        
        $this->assertEquals($users[0]->id, $tasks[1]->user_id);
        $this->assertEquals($users[1]->id, $tasks[0]->user_id);
    }

    public function testChangeTaskStatus()
    {
        $member = User::factory()->create();
        $member->assignRole(config('staticdata.roles.product_owner'));

        Sanctum::actingAs(
            $member,
            ['*']
        );

        $task = Task::factory()->create(
            [
                'user_id' => $member->id
            ]
        );

        $payload = [
            'status' => config('staticdata.tasks.status.in_progress')
        ];

        $expectedResponse = [
            'success' =>  true,
            'message' => 'Updated task successfully'
        ];

        $response = $this->put(route('api.update.task', ['task' => $task->id]), $payload);
        $response->assertJson($expectedResponse);
        $response->assertStatus(200);

        $this->assertDatabaseHas(
            'tasks',
            [
                'id' => $task->id,
                'status' => config('staticdata.tasks.status.in_progress')
            ]
        );
    }
}
