<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (config('staticdata.roles') as $role) {
            Role ::updateOrCreate(
                ['name' => $role], 
                ['guard_name' => 'web']
            );
        }
    }
}
