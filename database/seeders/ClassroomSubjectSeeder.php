<?php

namespace Database\Seeders;

use App\Models\AcademicSession;
use App\Models\Classroom;
use App\Models\Subject;
use Database\Factories\ClassroomFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;

class ClassroomSubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $classroom = Classroom::first();
        $subject = Subject::first();
        $academicSession = AcademicSession::first();

        if (is_null($classroom)) {
            Artisan::call('db:seed', ['--class' => 'ClassroomSeeder']);
        }

        if (is_null($academicSession)) {
            Artisan::call('db:seed', ['--class' => 'AcademicSessionSeeder']);
        }

        if (is_null($subject)) {
            Artisan::call('db:seed', ['--class' => 'SubjectSeeder']);
        }

        $subjects = Subject::all();
        $classrooms = Classroom::all();
        $academicSessions = AcademicSession::all();

        /**
         * generate 9 random subject for a classroom for each academic session
         */
        foreach ($academicSessions as $academicSession) {
            foreach ($classrooms as $classroom) {
                $randomSubjects = $subjects->random(9);
                foreach ($randomSubjects as $randomSubject) {
                    /**get a row that has the current academic_session_id and the subject_id that is about to be 
                    attahched
                     */
                    $row = $classroom->subjects()->where('academic_session_id', $academicSession->id)->where('subject_id', $randomSubject->id);
                    $allSubjects = $classroom->subjects()->where('academic_session_id', $academicSession->id);

                    /**
                     * if the row does not exists and the number of subjects in the classroom is less than 9
                     * then it can be attached to the current classroom
                     */
                    if (!$row->exists() && $allSubjects->count() < 9) {
                        $data = [$randomSubject->id => ['academic_session_id' => $academicSession->id]];
                        $classroom->subjects()->attach($data);
                    }
                }
            }
        }
    }
}
