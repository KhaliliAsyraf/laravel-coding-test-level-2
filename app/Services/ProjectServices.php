<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use App\Models\Project;

class ProjectServices
{
    public function getProjects($queryParam)
    {
        try {
            $keyword = !empty($queryParam['q']) ? $queryParam['q'] : null;
            $sortBy = !empty($queryParam['sortBy']) ? $queryParam['sortBy'] : 'name';
            $sortDirection = !empty($queryParam['sortDirection']) ? $queryParam['sortDirection'] : 'desc';
            $pageIndex = !empty($queryParam['pageIndex']) ? $queryParam['pageIndex'] : 0;
            $pageSize = !empty($queryParam['pageSize']) ? $queryParam['pageSize'] : 3;      

            $projects = Project::where(
                    function ($query) use ($keyword) {
                        if (!empty($keyword)) {
                            return $query->orWhere('name', 'like', '%' . $keyword . '%');
                        }
                    }
                )
                ->orderBy($sortBy, $sortDirection)
                ->paginate($pageSize, ['*'], $pageIndex);

            return $projects;
        } catch (\Exception $e) {
            Log::error('(Error) Retrieve projects failed. Error: ' . PHP_EOL . $e->getMessage());
            throw new \Exception('Retrieve projects failed.');
        }
    }

    public function getProject($id)
    {
        try {
            $project = Project::find($id);
            return $project;
        } catch (\Exception $e) {
            Log::error('(Error) Retrieve project failed. Error: ' . PHP_EOL . $e->getMessage());
            throw new \Exception('Retrieve project failed.');
        }
    }

    public function createProject($input)
    {
        try {
            DB::beginTransaction();

            $uuid = (string) Str::uuid();
            $project = Project::create(
                [
                    'id' => $uuid,
                    'name' => $input['name']
                ]
            );

            DB::commit();
            return $project;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('(Error) Create project failed. Error: ' . PHP_EOL . $e->getMessage());
            throw new \Exception('Create project failed.');
        }
    }

    public function updateProject($input)
    {
        try {
            DB::beginTransaction();

            $project = Project::find($input['id']);
            $project->update(
                Arr::only($input, app(Project::class)->getFillable())
            );

            DB::commit();
            return $project;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('(Error) Update project failed. Error: ' . PHP_EOL . $e->getMessage());
            throw new \Exception('Update project failed.');
        }
    }

    public function deleteProject($id)
    {
        try {
            DB::beginTransaction();

            $project = Project::find($id);
            $project->delete();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('(Error) Delete project failed. Error: ' . PHP_EOL . $e->getMessage());
            throw new \Exception('Delete project failed.');
        }
    }
}
