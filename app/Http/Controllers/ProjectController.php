<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ProjectServices;
use Illuminate\Support\Facades\Validator;
use App\Helpers\ValidationHelper;

class ProjectController extends BaseController
{
    private $projectService;

    public function __construct()
    {
        $this->projectService = new ProjectServices();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $data = $this->projectService->getProjects();
            return $this->sendResponse('Retrieved projects successfully', $data);
        } catch (\Exception $e) {
            return $this->sendError('Retrieved projects failed.', $e->getMessage(), 500);
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
            $validator = Validator::make($input, ValidationHelper::getCreateProjectRules());
    
            if ($validator->fails()) {
                return $this->sendError('Unprocessable Entity.', $validator->errors(), 422);
            }

            $data = $this->projectService->createProject($input);
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
    public function show($project)
    {
        try {
            $id = $project;
            $validator = Validator::make(
                [
                    'id' => $id
                ],
                [
                    'id' => 'required|uuid|exists:projects,id'
                ]
            );

            if ($validator->fails()) {
                return $this->sendError('Unprocessable Entity.', $validator->errors(), 422);
            }

            $data = $this->projectService->getProject($id);
            return $this->sendResponse('Retrieved project successfully', $data);
        } catch (\Exception $e) {
            return $this->sendError('Retrieved project failed.', $e->getMessage(), 500);
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
    public function update(Request $request, $project)
    {
        try {
            $input = $request->all();
            $input['id'] = $project;
            $validator = Validator::make($input, ValidationHelper::getUpdateProjectRules());

            if ($validator->fails()) {
                return $this->sendError('Unprocessable Entity.', $validator->errors(), 422);
            }

            $data = $this->projectService->updateProject($input);
            return $this->sendResponse('Updated project successfully', $data);
        } catch (\Exception $e) {
            return $this->sendError('Updated project failed.', $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($project)
    {
        try {
            $id = $project;
            $validator = Validator::make(
                [
                    'id' => $id
                ],
                [
                    'id' => 'required|uuid|exists:projects,id'
                ]
            );

            if ($validator->fails()) {
                return $this->sendError('Unprocessable Entity.', $validator->errors(), 422);
            }

            $data = $this->projectService->deleteProject($id);
            return $this->sendResponse('Deleted project successfully');
        } catch (\Exception $e) {
            return $this->sendError('Deleted project failed.', $e->getMessage(), 500);
        }
    }
}
