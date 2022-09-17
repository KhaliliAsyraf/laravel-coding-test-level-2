<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(
            [
                RolesTableSeeder::class,
                PermissionsTableSeeder::class,
            ]
        );

        $admin = \App\Models\User::factory()->create(
            [
                'name' => 'admin',
                'email' => 'admin@test.my',
                'password' => bcrypt('password123')
            ]
        );
        $admin->assignRole([config('staticdata.roles.admin')]);

        $productOwner = \App\Models\User::factory()->create(
            [
                'name' => 'owner',
                'email' => 'owner@test.my',
                'password' => bcrypt('password123')
            ]
        );
        $productOwner->assignRole([config('staticdata.roles.product_owner')]);

        $users = \App\Models\User::factory(5)->create();
        foreach ($users as $user) {
            $user->assignRole([config('staticdata.roles.member')]);
            $project = \App\Models\Project::factory()->create();
            \App\Models\Task::factory()->create(
                [
                    'user_id' => $user->id,
                    'project_id' => $project->id
                ]
            );
        }
    }
}
