<?php

namespace App\Helpers;

class ValidationHelper
{
    public static function getLoginRules()
    {
        return [
            'email' => 'required|exists:users,email',
            'password' => 'required'
        ];
    }

    public static function getUserDetailsRules()
    {
        return [
            'id' => 'required|uuid'
        ];
    }

    public static function getCreateUserRules()
    {
        return [
            'email' => 'required|email|unique:users,email',
            'password' => 'nullable',
            'name' => 'required|string'
        ];
    }

    public static function getUpdateUserRules()
    {
        return [
            'id' => 'required|exists:users,id',
            'email' => 'required|email|unique:users,email',
            'name' => 'required|string'
        ];
    }

    public static function getCreateProjectRules()
    {
        return [
            'name' => 'required|string'
        ];
    }

    public static function getUpdateProjectRules()
    {
        return [
            'id' => 'required|exists:projects,id',
            'name' => 'required|string'
        ];
    }

    public static function getCreateTaskRules()
    {
        return [
            'title' => 'required|string',
            'description' => 'nullable|string',
            'project_id' => 'required|exists:projects,id',
            'user_id' => 'required|exists:users,id'
        ];
    }

    public static function getUpdateTaskRules()
    {
        return [
            'id' => 'required|exists:tasks,id',
            'title' => 'required|string',
            'description' => 'nullable|string',
            'status' => 'required|string',
            'project_id' => 'required|exists:projects,id',
            'user_id' => 'required|exists:users,id'
        ];
    }
}