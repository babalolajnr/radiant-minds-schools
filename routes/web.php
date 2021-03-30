<?php

use App\Http\Controllers\AcademicSessionController;
use App\Http\Controllers\ADController;
use App\Http\Controllers\ADTypeController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GuardianController;
use App\Http\Controllers\PDController;
use App\Http\Controllers\PDTypeController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\TermController;
use App\Http\Controllers\UserController;
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

Route::middleware(['auth', 'verified', 'isActiveAndVerified'])->group(function () {

    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::prefix('users')->name('user.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::patch('/verify/{user:email}', [UserController::class, 'verify'])->name('verify');
        Route::patch('/toggle-status/{user:email}', [UserController::class, 'toggleStatus'])->name('toggle-status');
        Route::delete('/delete/{user:email}', [UserController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('teachers')->name('teacher.')->group(function () {
        //Teacher routes
        Route::get('/', [TeacherController::class, 'index'])->name('index');
        Route::get('/create', [TeacherController::class, 'create'])->name('create');
        Route::get('/view/{teacher:slug}', [TeacherController::class, 'show'])->name('show');
        Route::get('/edit/{teacher:slug}', [TeacherController::class, 'edit'])->name('edit');
        Route::post('/store', [TeacherController::class, 'store'])->name('store');
        Route::patch('/update/{teacher:slug}', [TeacherController::class, 'update'])->name('update');
        Route::patch('/activate/{teacher}', [TeacherController::class, 'activate'])->name('activate');
        Route::patch('/deactivate/{teacher}', [TeacherController::class, 'deactivate'])->name('deactivate');
        Route::delete('/delete/{teacher}', [TeacherController::class, 'destroy'])->name('destroy');
    });


    Route::prefix('students')->name('student.')->group(function () {
        //Student Routes
        Route::get('/', [StudentController::class, 'index'])->name('index');
        Route::get('/create', [StudentController::class, 'create'])->name('create');
        Route::get('/student-settings/{student:admission_no}', [StudentController::class, 'showStudentSettingsView'])->name('show.student.settingsView');
        Route::get('view/{student:admission_no}', [StudentController::class, 'show'])->name('show');
        Route::get('/edit/{student:admission_no}', [StudentController::class, 'edit'])->name('edit');
        Route::get('/results/sessional/{student:admission_no}/{academicSessionName}', [StudentController::class, 'getSessionalResults'])->name('get.sessional.results')->where('academicSessionName', '.*');
        Route::get('/results/term/{student:admission_no}/{termSlug}/{academicSessionName}', [StudentController::class, 'getTermResults'])->name('get.term.results')->where('academicSessionName', '.*');
        Route::get('/trashed', [StudentController::class, 'showTrashed'])->name('show.trashed');
        Route::get('/alumni', [StudentController::class, 'getAlumni'])->name('get.alumni');
        Route::post('/store/image/{student}', [StudentController::class, 'uploadImage'])->name('upload.image');
        Route::post('/store', [StudentController::class, 'store'])->name('store');
        Route::patch('/update/{student}', [StudentController::class, 'update'])->name('update');
        Route::patch('/activate/{student}', [StudentController::class, 'activate'])->name('activate');
        Route::patch('/deactivate/{student}', [StudentController::class, 'deactivate'])->name('deactivate');
        Route::patch('/promote/{student}', [StudentController::class, 'promote'])->name('promote');
        Route::patch('/demote/{student}', [StudentController::class, 'demote'])->name('demote');
        Route::patch('/restore/{id}', [StudentController::class, 'restore'])->name('restore');
        Route::delete('/delete/{student}', [StudentController::class, 'destroy'])->name('destroy');
        Route::delete('/force-delete/{id}', [StudentController::class, 'forceDelete'])->name('force.delete');
    });

    Route::prefix('classrooms')->name('classroom.')->group(function () {
        //Classroom Routes
        Route::get('/', [ClassroomController::class, 'index'])->name('index');
        Route::get('/edit/{classroom:slug}', [ClassroomController::class, 'edit'])->name('edit');
        Route::get('/view/{classroom:slug}', [ClassroomController::class, 'show'])->name('show');
        Route::get('/set-subjects/{classroom:slug}', [ClassroomController::class, 'setSubjects'])->name('set.subjects');
        Route::post('/update-subjects/{classroom:slug}', [ClassroomController::class, 'updateSubjects'])->name('update.subjects');
        Route::post('/store', [ClassroomController::class, 'store'])->name('store');
        Route::patch('/assign-teacher/{classroom:slug}/{teacherSlug}', [ClassroomController::class, 'assignTeacher'])->name('assign.teacher');
        Route::patch('/update/{classroom:slug}', [ClassroomController::class, 'update'])->name('update');
        Route::delete('/delete/{classroom:slug}', [ClassroomController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('terms')->name('term.')->group(function () {
        //Term routes
        Route::get('/', [TermController::class, 'index'])->name('index');
        Route::get('/edit/{term:slug}', [TermController::class, 'edit'])->name('edit');
        Route::post('/store', [TermController::class, 'store'])->name('store');
        Route::patch('/update/{term:slug}', [TermController::class, 'update'])->name('update');
        Route::delete('/delete/{term:slug}', [TermController::class, 'destroy'])->name('destroy');
    });


    Route::prefix('subjects')->name('subject.')->group(function () {
        // Subject routes
        Route::get('/', [SubjectController::class, 'index'])->name('index');
        Route::get('/edit/{subject:slug}', [SubjectController::class, 'edit'])->name('edit');
        Route::post('/store', [SubjectController::class, 'store'])->name('store');
        Route::patch('/update/{subject:slug}', [SubjectController::class, 'update'])->name('update');
        Route::delete('/delete/{subject:slug}', [SubjectController::class, 'destroy'])->name('destroy');
    });


    Route::prefix('academic-sessions')->name('academic-session.')->group(function () {
        //AcademicSession routes
        Route::get('/', [AcademicSessionController::class, 'index'])->name('index');
        Route::get('/edit/{academicSession:name}', [AcademicSessionController::class, 'edit'])->name('edit')->where('academicSession', '.*');
        Route::get('/set-current/{academicSession:name}', [AcademicSessionController::class, 'setCurrentAcademicSession'])->name('set.current')->where('academicSession', '.*');
        Route::post('/store', [AcademicSessionController::class, 'store'])->name('store');
        Route::patch('/update/{academicSession:name}', [AcademicSessionController::class, 'update'])->name('update')->where('academicSession', '.*');
        Route::delete('/delete/{academicSession:name}', [AcademicSessionController::class, 'destroy'])->name('destroy')->where('academicSession', '.*');
    });

    Route::prefix('guardians')->name('guardian.')->group(function () {
        //Guardian Routes
        Route::get('/edit/{guardian:phone}', [GuardianController::class, 'edit'])->name('edit');
        Route::patch('/update/{guardian:phone}', [GuardianController::class, 'update'])->name('update');
    });


    Route::prefix('results')->name('result.')->group(function () {
        //Result ROutes
        Route::get('/create/{student:admission_no}', [ResultController::class, 'create'])->name('create');
        Route::get('/performance-report/{student:admission_no}/{termSlug}/{academicSessionName}', [ResultController::class, 'showPerformanceReport'])->name('show.performance')->where('academicSessionName', '.*');
        Route::get('/edit/{result}', [ResultController::class, 'edit'])->name('edit');
        Route::post('/store/{student}', [ResultController::class, 'store'])->name('store');
        Route::patch('/update/{result}', [ResultController::class, 'update'])->name('update');
        Route::delete('/delete/{result}', [ResultController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('pds')->name('pd.')->group(function () {
        //Pychomotor Domain Routes
        Route::get('/create/{student:admission_no}/{termSlug}/{academicSessionName?}', [PDController::class, 'create'])->name('create')->where('academicSessionName', '.*');
        Route::post('/store/{student}/{termId}/{academicSessionId?}', [PDController::class, 'store'])->name('store');
    });

    Route::prefix('pd-types')->name('pd-type.')->group(function () {
        //Pychomotor domain type routes
        Route::get('/', [PDTypeController::class, 'index'])->name('index');
        Route::get('/edit/{pdType:slug}', [PDTypeController::class, 'edit'])->name('edit');
        Route::post('/store', [PDTypeController::class, 'store'])->name('store');
        Route::patch('/update/{pdType}', [PDTypeController::class, 'update'])->name('update');
        Route::delete('/delete/{pdType}', [PDTypeController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('ads')->name('ad.')->group(function () {
        //Affective Domain Routes
        Route::get('/create/{student:admission_no}/{termSlug}/{academicSessionName?}', [ADController::class, 'create'])->name('create')->where('academicSessionName', '.*');
        Route::post('/store/{student}/{termId}/{academicSessionId?}', [ADController::class, 'store'])->name('store');
    });

    Route::prefix('ad-types')->name('ad-type.')->group(function () {
        //Affective domain type routes
        Route::get('/', [ADTypeController::class, 'index'])->name('index');
        Route::get('/edit/{adType:slug}', [ADTypeController::class, 'edit'])->name('edit');
        Route::post('/store', [ADTypeController::class, 'store'])->name('store');
        Route::patch('/update/{adType}', [ADTypeController::class, 'update'])->name('update');
        Route::delete('/delete/{adType}', [ADTypeController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('attendance')->name('attendance.')->group(function () {
        //Attendance Domain Routes
        Route::get('/create/{student:admission_no}/{termSlug}/{academicSessionName?}', [AttendanceController::class, 'create'])->name('create')->where('academicSessionName', '.*');
        Route::post('/store/{student}/{termId}/{academicSessionId?}', [AttendanceController::class, 'storeOrUpdate'])->name('store');
    });
});



require __DIR__ . '/auth.php';
