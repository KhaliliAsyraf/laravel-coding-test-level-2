<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use App\Models\Task;
use App\Models\Project;
use App\Models\User;

class TaskFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Task::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->word(),
            'description' => $this->faker->text(50),
            'status' => Arr::random(config('staticdata.tasks.status')),
            'project_id' => Project::factory()->create()->id,
            'user_id' => User::factory()->create()->id
        ];
    }
}
