<?php

use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group( function () {

    //Teacher routes
    Route::get('/create/teacher', [TeacherController::class, 'create']);
    Route::get('/view/teacher/{slug}', [TeacherController::class, 'show']);
    Route::get('/edit/teacher/{slug}', [TeacherController::class, 'edit']);
    Route::post('/store/teacher', [TeacherController::class, 'store']);
    Route::patch('/update/teacher/{slug}', [TeacherController::class, 'update']);
    Route::patch('/suspend/teacher/{id}', [TeacherController::class, 'suspend']);
    Route::patch('/activate/teacher/{id}', [TeacherController::class, 'activate']);
    Route::patch('/deactivate/teacher/{id}', [TeacherController::class, 'deactivate']);
    Route::patch('/restore/teacher/{id}', [TeacherController::class, 'restore']);
    Route::delete('/delete/teacher/{id}', [TeacherController::class, 'destroy']);
    Route::delete('/forceDelete/teacher/{id}', [TeacherController::class, 'forceDelete']);

    //Student Routes
    Route::post('/store/student', [StudentController::class, 'store']);
});

require __DIR__.'/auth.php';
