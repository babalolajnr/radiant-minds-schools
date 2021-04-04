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
            $classroomPopulationChartData = $this->generateClassroomsPopulationChart();

            $dashboardData = [
                'students' => $students,
                'alumni' => $alumni,
                'teachers' => $teachers,
                'users' => $users,
                'classrooms' => $classrooms,
                'academicSession' => $academicSession,
                'subjects' => $subjects,
                'classroomPopulationChartData' => $classroomPopulationChartData
            ];

            return $dashboardData;
        });



        return view("dashboard", compact(
            'dashboardData',
        ));
    }

    /** Generates data for the classrooms population chart
     * @return array
     * 
     */
    private function generateClassroomsPopulationChart()
    {
        $classrooms = Classroom::all();
        $classroomNames = [];
        $populations = [];
        $colors = [];

        foreach ($classrooms as $classroom) {
            array_push($classroomNames, $classroom->name);

            //get students that have not graduated for each class and count them
            $population = $classroom->students->whereNull('graduated_at');
            array_push($populations, count($population));

            //push random colors into array
            array_push($colors, Color::hexcolor());
        }

        return [
            'classroomNames' => $classroomNames,
            'populations' => $populations,
            'colors' => $colors
        ];
    }
}
