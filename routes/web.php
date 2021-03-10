<?php

use App\Http\Controllers\AcademicSessionController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\GuardianController;
use App\Http\Controllers\PDController;
use App\Http\Controllers\PDTypesController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\TermController;
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

Route::middleware(['auth', 'verified'])->group(function () {

    Route::prefix('teachers')->name('teacher.')->group(function () {
        //Teacher routes
        Route::get('/create', [TeacherController::class, 'create'])->name('create');
        Route::get('/view/{slug}', [TeacherController::class, 'show'])->name('show');
        Route::get('/edit/{slug}', [TeacherController::class, 'edit'])->name('edit');
        Route::post('/store', [TeacherController::class, 'store'])->name('store');
        Route::patch('/update/{slug}', [TeacherController::class, 'update'])->name('update');
        Route::patch('/suspend/{id}', [TeacherController::class, 'suspend'])->name('suspend');
        Route::patch('/activate/{id}', [TeacherController::class, 'activate'])->name('activate');
        Route::patch('/deactivate/{id}', [TeacherController::class, 'deactivate'])->name('deactivate');
        Route::patch('/restore/{id}', [TeacherController::class, 'restore'])->name('restore');
        Route::delete('/delete/{id}', [TeacherController::class, 'destroy'])->name('destroy');
        Route::delete('/force-delete/{id}', [TeacherController::class, 'forceDelete'])->name('force.delete');
    });


    Route::prefix('students')->name('student.')->group(function () {
        //Student Routes
        Route::get('/index', [StudentController::class, 'index'])->name('index');
        Route::get('/create', [StudentController::class, 'create'])->name('create');
        /**
         * {student} stands for admission number
         * so wherever you see it in the routes
         * that's what it means. I don't know what
         * I was thinking when i decided to name it like that
         * but I am too lazy to change it now.
         */
        Route::get('/student-settings/{student}', [StudentController::class, 'showStudentSettingsView'])->name('show.student.settingsView');
        Route::get('view/{student}', [StudentController::class, 'show'])->name('show');
        Route::get('/edit/{student}', [StudentController::class, 'edit'])->name('edit');
        Route::get('subjects/{student}', [StudentController::class, 'getSubjects'])->name('get.subjects');
        Route::get('/results/sessional/{student}/{academicSessionId}', [StudentController::class, 'getSessionalResults'])->name('get.sessional.results');
        Route::get('/results/term/{student}/{termId}/{academicSessionId}', [StudentController::class, 'getTermResults'])->name('get.term.results');
        Route::get('/trashed', [StudentController::class, 'showTrashed'])->name('show.trashed');
        Route::post('/store/image/{id}', [StudentController::class, 'uploadImage'])->name('upload.image');
        Route::post('/store', [StudentController::class, 'store'])->name('store');
        Route::patch('/update/{id}', [StudentController::class, 'update'])->name('update');
        Route::patch('/suspend/{id}', [StudentController::class, 'suspend'])->name('suspend');
        Route::patch('/activate/{id}', [StudentController::class, 'activate'])->name('activate');
        Route::patch('/deactivate/{id}', [StudentController::class, 'deactivate'])->name('deactivate');
        Route::patch('/promote/{id}', [StudentController::class, 'promote'])->name('promote');
        Route::patch('/demote/{id}', [StudentController::class, 'demote'])->name('demote');
        Route::delete('/delete/{id}', [StudentController::class, 'destroy'])->name('destroy');
        Route::delete('/force-delete/{id}', [StudentController::class, 'forceDelete'])->name('force.delete');
    });

    Route::prefix('classrooms')->name('classroom.')->group(function () {
        //Classroom Routes
        Route::get('/', [ClassroomController::class, 'index'])->name('index');
        Route::get('/edit/{id}', [ClassroomController::class, 'edit'])->name('edit');
        Route::get('/view/{id}', [ClassroomController::class, 'show'])->name('show');
        Route::get('/set-subjects/{id}', [ClassroomController::class, 'setSubjects'])->name('set.subjects');
        Route::post('/store', [ClassroomController::class, 'store'])->name('store');
        Route::post('/update-subjects/{id}', [ClassroomController::class, 'updateSubjects'])->name('update.subjects');
        Route::patch('/update/{id}', [ClassroomController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [ClassroomController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('terms')->name('term.')->group(function () {
        //Term routes
        Route::get('/', [TermController::class, 'index'])->name('index');
        Route::get('/edit/{id}', [TermController::class, 'edit'])->name('edit');
        Route::post('/store', [TermController::class, 'store'])->name('store');
        Route::patch('/update/{id}', [TermController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [TermController::class, 'destroy'])->name('destroy');
    });


    Route::prefix('subjects')->name('subject.')->group(function () {
        // Subject routes
        Route::get('/', [SubjectController::class, 'index'])->name('index');
        Route::get('/edit/{id}', [SubjectController::class, 'edit'])->name('edit');
        Route::post('/store', [SubjectController::class, 'store'])->name('store');
        Route::patch('/update/{id}', [SubjectController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [SubjectController::class, 'destroy'])->name('destroy');
    });


    Route::prefix('academic-sessions')->name('academic-session.')->group(function () {
        //AcademicSession routes
        Route::get('/', [AcademicSessionController::class, 'index'])->name('index');
        Route::get('/edit/{id}', [AcademicSessionController::class, 'edit'])->name('edit');
        Route::get('/set-current/{id}', [AcademicSessionController::class, 'setCurrentAcademicSession'])->name('set.current');
        Route::post('/store', [AcademicSessionController::class, 'store'])->name('store');
        Route::patch('/update/{id}', [AcademicSessionController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [AcademicSessionController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('guardians')->name('guardian.')->group(function () {
        //Guardian Routes
        Route::get('/edit/{phone}', [GuardianController::class, 'edit'])->name('edit');
        Route::patch('/update/{phone}', [GuardianController::class, 'update'])->name('update');
    });


    Route::prefix('results')->name('result.')->group(function () {
        //Result ROutes
        Route::get('/create/{student}', [ResultController::class, 'create'])->name('create');
        Route::get('/performance-report/{student}/{academicSessionId}/{termId}', [ResultController::class, 'showPerformanceReport'])->name('show.performance');
        Route::get('/edit/{id}', [ResultController::class, 'edit'])->name('edit');
        Route::post('/store/{student}', [ResultController::class, 'store'])->name('store');
        Route::patch('/update/{id}', [ResultController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [ResultController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('pds')->name('pd.')->group(function () {
        //Pychomotor Domain Routes
        Route::get('/create/{id}/{termId}/{academicSessionId?}', [PDController::class, 'create'])->name('create');
        Route::post('/store/{id}/{termId}/{academicSessionId?}', [PDController::class, 'store'])->name('store');
    });

    Route::prefix('pd-types')->name('pd-type.')->group(function () {
        //Pychomotor domain type routes
        Route::get('/', [PDTypesController::class, 'index'])->name('index');
        Route::get('/edit/{slug}', [PDTypesController::class, 'edit'])->name('edit');
        Route::post('/store', [PDTypesController::class, 'store'])->name('store');
        Route::patch('/update/{id}', [PDTypesController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [PDTypesController::class, 'destroy'])->name('destroy');
    });
});



require __DIR__ . '/auth.php';
