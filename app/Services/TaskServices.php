<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use App\Models\Project;
use App\Models\Task;

class TaskServices
{
    public function getTasks()
    {
        try {
            $tasks = Task::all();
            return $tasks;
        } catch (\Exception $e) {
            Log::error('(Error) Retrieve tasks failed. Error: ' . PHP_EOL . $e->getMessage());
            throw new \Exception('Retrieve tasks failed.');
        }
    }

    public function getTask($id)
    {
        try {
            $task = Task::find($id);
            return $task;
        } catch (\Exception $e) {
            Log::error('(Error) Retrieve task failed. Error: ' . PHP_EOL . $e->getMessage());
            throw new \Exception('Retrieve task failed.');
        }
    }

    public function createTask($input)
    {
        try {
            DB::beginTransaction();

            $data = Arr::only($input, app(Task::class)->getFillable());
            $task = Task::create($data);

            DB::commit();
            return $task;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('(Error) Create task failed. Error: ' . PHP_EOL . $e->getMessage());
            throw new \Exception('Create task failed.');
        }
    }

    public function updateTask($input)
    {
        try {
            DB::beginTransaction();

            $task = Task::find($input['id']);
            $task->update(
                Arr::only($input, app(Task::class)->getFillable())
            );

            DB::commit();
            return $task;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('(Error) Update task failed. Error: ' . PHP_EOL . $e->getMessage());
            throw new \Exception('Update task failed.');
        }
    }

    public function deleteTask($id)
    {
        try {
            DB::beginTransaction();

            $task = Task::find($id);
            $task->delete();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('(Error) Delete task failed. Error: ' . PHP_EOL . $e->getMessage());
            throw new \Exception('Delete task failed.');
        }
    }
}
