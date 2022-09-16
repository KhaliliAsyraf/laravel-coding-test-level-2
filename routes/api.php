<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(
    ['middleware' => 'auth:sanctum', 'as' => 'api.'],
    function () {
        Route::group(
            ['prefix' => 'v1'],
            function () {

                /**
                 * Example of direct set permission on route. Used Spatie package.
                 */

                // ----- USERS ----- //
                Route::group(
                    ['prefix' => 'users'],
                    function () {
                        Route::get('/', [App\Http\Controllers\UserController::class, 'getUsers'])->name('get.users')->middleware(['permission:view-users']);
                        Route::get('/{user_id}', [App\Http\Controllers\UserController::class, 'getUser'])->name('get.user')->middleware(['permission:view-user-details']);
                        Route::post('/', [App\Http\Controllers\UserController::class, 'createUser'])->name('create.user')->middleware(['permission:create-user']);
                        Route::put('/{user_id}', [App\Http\Controllers\UserController::class, 'updateUser'])->name('update.user')->middleware(['permission:update-user']);
                        // PUT and PATCH method almost same
                        Route::patch('/{user_id}', [App\Http\Controllers\UserController::class, 'updateUser'])->name('update.user')->middleware(['permission:update-user']);
                        Route::delete('/{user_id}', [App\Http\Controllers\UserController::class, 'deleteUser'])->name('delete.user')->middleware(['permission:delete-user']);
                    }
                );

                Route::group(
                    ['prefix' => 'projects'],
                    function () {
                        Route::get('/', [App\Http\Controllers\ProjectController::class, 'index'])->name('get.projects')->middleware(['permission:view-projects']);
                        Route::get('/{project}', [App\Http\Controllers\ProjectController::class, 'show'])->name('get.project')->middleware(['permission:view-project-details']);
                        Route::post('/', [App\Http\Controllers\ProjectController::class, 'store'])->name('create.project')->middleware(['permission:create-project']);
                        Route::put('/{project}', [App\Http\Controllers\ProjectController::class, 'update'])->name('update.project')->middleware(['permission:update-project']);
                        // PUT and PATCH method almost same
                        Route::patch('/{project}', [App\Http\Controllers\ProjectController::class, 'update'])->name('update.project')->middleware(['permission:update-project']);
                        Route::delete('/{project}', [App\Http\Controllers\ProjectController::class, 'destroy'])->name('delete.project')->middleware(['permission:delete-project']);
                    }
                );

                Route::group(
                    ['prefix' => 'tasks'],
                    function () {
                        Route::get('/', [App\Http\Controllers\TaskController::class, 'index'])->name('get.tasks')->middleware(['permission:view-task']);
                        Route::get('/{task}', [App\Http\Controllers\TaskController::class, 'show'])->name('get.task')->middleware(['permission:view-task-details']);
                        Route::post('/', [App\Http\Controllers\TaskController::class, 'store'])->name('create.task')->middleware(['permission:create-task']);
                        Route::put('/{task}', [App\Http\Controllers\TaskController::class, 'update'])->name('update.task')->middleware(['permission:update-task']);
                        // PUT and PATCH method almost same
                        Route::patch('/{task}', [App\Http\Controllers\TaskController::class, 'update'])->name('update.task')->middleware(['permission:update-task']);
                        Route::delete('/{task}', [App\Http\Controllers\TaskController::class, 'destroy'])->name('delete.task')->middleware(['permission:delete-task']);
                    }
                );
            }
        );
    }
);

Route::match(['get', 'post'], '/v1/login', [App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login');
