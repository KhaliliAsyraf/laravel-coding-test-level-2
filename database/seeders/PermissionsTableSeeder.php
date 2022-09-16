<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use App\Models\Role;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class PermissionsTableSeeder extends Seeder
{
    protected $data = [];

    public function run()
    {
        $this->data['users'] = [
            'guard' => 'web',
            'group_display_name' => 'User Management',
            'permissions' => [
                'create-user' => [
                    'display_name' => 'Create User',
                    'role_access' => [
                        'ADMIN',
                    ]
                ],
                'update-user' => [
                    'display_name' => 'Update User',
                    'role_access' => [
                        'ADMIN',
                    ]
                ],
                'view-users' => [
                    'display_name' => 'View Users',
                    'role_access' => [
                        'ADMIN',
                    ]
                ],
                'view-user-details' => [
                    'display_name' => 'View User Details',
                    'role_access' => [
                        'ADMIN',
                    ]
                ],
                'delete-user' => [
                    'display_name' => 'Delete User',
                    'role_access' => [
                        'ADMIN',
                    ]
                ],
            ]
        ];

        $this->data['projects'] = [
            'guard' => 'web',
            'group_display_name' => 'Project Management',
            'permissions' => [
                'create-project' => [
                    'display_name' => 'Create Project',
                    'role_access' => [
                        'PRODUCT_OWNER',
                    ]
                ],
                'update-project' => [
                    'display_name' => 'Update Project',
                    'role_access' => [
                        'ADMIN',
                        'PRODUCT_OWNER'
                    ]
                ],
                'view-projects' => [
                    'display_name' => 'View Projects',
                    'role_access' => [
                        'ADMIN',
                        'PRODUCT_OWNER'
                    ]
                ],
                'view-project-details' => [
                    'display_name' => 'View User Details',
                    'role_access' => [
                        'ADMIN',
                        'PRODUCT_OWNER'
                    ]
                ],
                'delete-project' => [
                    'display_name' => 'Delete User',
                    'role_access' => [
                        'ADMIN',
                        'PRODUCT_OWNER'
                    ]
                ],
            ]
        ];

        $this->data['tasks'] = [
            'guard' => 'web',
            'group_display_name' => 'Project Management',
            'permissions' => [
                'create-task' => [
                    'display_name' => 'Create Task',
                    'role_access' => [
                        'PRODUCT_OWNER',
                    ]
                ],
                'update-task' => [
                    'display_name' => 'Update Task',
                    'role_access' => [
                        'ADMIN',
                        'PRODUCT_OWNER'
                    ]
                ],
                'view-tasks' => [
                    'display_name' => 'View Tasks',
                    'role_access' => [
                        'ADMIN',
                        'PRODUCT_OWNER'
                    ]
                ],
                'view-task-details' => [
                    'display_name' => 'View User Task',
                    'role_access' => [
                        'ADMIN',
                        'PRODUCT_OWNER'
                    ]
                ],
                'delete-task' => [
                    'display_name' => 'Delete Task',
                    'role_access' => [
                        'ADMIN',
                        'PRODUCT_OWNER'
                    ]
                ],
            ]
        ];

        try {
            DB::beginTransaction();
            $roles = config('staticdata.roles');
            foreach ($roles as $role) {
                $roles[$role] = Role::findByName($role);
            }

            $permissionsNameOnly = [];

            foreach ($this->data as $data) {
                array_push($permissionsNameOnly, array_keys($data['permissions']));
            }

            $permissionsNameOnly = Arr::flatten($permissionsNameOnly);
            Cache::forget('spatie.permission.cache');

            // to cater if someone remove or rename the permission.
            $permissionsToDelete = Permission::whereNotIn('name', $permissionsNameOnly)->get(['id']);
            if (!empty($permissionsToDelete)) {
                $permissionsToDelete = $permissionsToDelete->toArray();
                DB::table('role_has_permissions')->whereIn('permission_id', $permissionsToDelete)->delete();
                DB::table('model_has_permissions')->whereIn('permission_id', $permissionsToDelete)->delete();
                Permission::whereIn('id', $permissionsToDelete)->delete();
            }

            foreach ($this->data as $group => $group_details) {
                foreach ($group_details['permissions'] as $permission_name => $permission_details) {
                    $permission = Permission::updateOrCreate(
                        [
                            'name' => $permission_name
                        ],
                        [
                            'guard_name' => $group_details['guard'],
                            'display_name' => $permission_details['display_name'],
                            'group' => $group,
                            'group_display_name' => $group_details['group_display_name']
                        ]
                    );

                    foreach ($permission_details['role_access'] as $role_name) {
                        $roles[$role_name]->givePermissionTo($permission);
                    }
                }
            }
            app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

            Log::info('Permissions seeder run succesfully');
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('(Error) Permission seeder failed. Error: ' . PHP_EOL . $e->getMessage());
            throw new \Exception('Permission seeder failed.');
        }
    }
}
