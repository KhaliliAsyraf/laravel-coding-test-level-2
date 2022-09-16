<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\ValidationHelper;
use App\Models\Task;
use App\Services\TaskServices;

class TaskController extends BaseController
{
    private $taskService;

    public function __construct()
    {
        $this->taskService = new TaskServices();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $data = $this->taskService->getTasks();
            return $this->sendResponse('Retrieved tasks successfully', $data);
        } catch (\Exception $e) {
            return $this->sendError('Retrieved tasks failed.', $e->getMessage, 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $input = $request->all();
            $validator = Validator::make($input, ValidationHelper::getCreateTaskRules());
    
            if ($validator->fails()) {
                return $this->sendError('Unprocessable Entity.', $validator->errors(), 422);
            }

            $data = $this->taskService->createTask($input);
            return $this->sendResponse('Created project successfully', $data);
        } catch (\Exception $e) {
            return $this->sendError('Create project failed', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($task)
    {
        try {
            $id = $task;
            $validator = Validator::make(
                [
                    'id' => $id
                ],
                [
                    'id' => 'required|uuid|exists:tasks,id'
                ]
            );

            if ($validator->fails()) {
                return $this->sendError('Unprocessable Entity.', $validator->errors(), 422);
            }

            $data = $this->taskService->getTask($id);
            return $this->sendResponse('Retrieved task successfully', $data);
        } catch (\Exception $e) {
            return $this->sendError('Retrieved task failed.', $e->getMessage, 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $task)
    {
        try {
            $input = $request->all();
            $input['id'] = $task;
            $validator = Validator::make($input, ValidationHelper::getUpdateTaskRules());

            if ($validator->fails()) {
                return $this->sendError('Unprocessable Entity.', $validator->errors(), 422);
            }

            $data = $this->taskService->updateTask($input);
            return $this->sendResponse('Updated task successfully', $data);
        } catch (\Exception $e) {
            return $this->sendError('Updated task failed.', $e->getMessage, 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($task)
    {
        try {
            $id = $task;
            $validator = Validator::make(
                [
                    'id' => $id
                ],
                [
                    'id' => 'required|uuid|exists:tasks,id'
                ]
            );

            if ($validator->fails()) {
                return $this->sendError('Unprocessable Entity.', $validator->errors(), 422);
            }

            $data = $this->taskService->deleteTask($id);
            return $this->sendResponse('Deleted task successfully');
        } catch (\Exception $e) {
            return $this->sendError('Deleted task failed.', $e->getMessage, 500);
        }
    }
}
