<?php

namespace App\Http\Controllers;

use App\Models\AcademicSession;
use App\Models\Classroom;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Faker\Provider\Color;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache as FacadesCache;


class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {


        $dashboardData = FacadesCache::remember('dashboardData', 60 * 60, function () {

            $students = count(Student::getAllStudents());
            $alumni = count(Student::getAlumni());
            $teachers = count(Teacher::all());
            $users = count(User::all());
            $classrooms = count(Classroom::all());
            $academicSession = AcademicSession::currentAcademicSession();
            $subjects = count(Subject::all());

            $dashboardData = [
                'students' => $students,
                'alumni' => $alumni,
                'teachers' => $teachers,
                'users' => $users,
                'classrooms' => $classrooms,
                'academicSession' => $academicSession,
                'subjects' => $subjects
            ];

            return $dashboardData;
        });

        $classrooms = Classroom::all();
        $classroomNames = [];
        $populations = [];
        $colors = [];

        foreach ($classrooms as $classroom) {
            array_push($classroomNames, $classroom->name);
            $population = $classroom->students->whereNull('graduated_at');
            array_push($populations, count($population));
            array_push($colors, Color::hexcolor());
        }

        return view("dashboard", compact(
            'dashboardData',
            'classroomNames',
            'populations',
            'colors'
        ));
    }
}
