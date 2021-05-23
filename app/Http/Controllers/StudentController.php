<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\AcademicSession;
use App\Models\Result;
use App\Models\Classroom;
use App\Models\PDType;
use App\Models\Period;
use App\Models\Student;
use App\Models\Term;
use App\Services\StudentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{

    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        $students = Student::whereNull('graduated_at')->get()->sortByDesc('created_at');
        $academicSessions = AcademicSession::all()->sortByDesc('created_at');
        $terms = Term::all()->sortByDesc('created_at');

        return view('students', compact('students', 'academicSessions', 'terms'));
    }

    /**
     * Get Alumni
     *
     * @return void
     */
    public function getAlumni()
    {
        $students = Student::whereNotNull('graduated_at')->get();
        return view('alumni', compact('students'));
    }

    /**
     * create student
     *
     * @return void
     */
    public function create()
    {
        $classrooms = Classroom::pluck('name')->all();
        return view('createStudent', compact('classrooms'));
    }


    /**
     * store student
     *
     * @param  StoreStudentRequest $request
     * @param  StudentService $studentService
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function store(StoreStudentRequest $request, StudentService $studentService)
    {
        $studentService->store($request);
        return redirect()->route('student.index')->with('success', 'Student Added!');
    }

    /**
     * show student
     *
     * @param  Student $student
     * @param  StudentService $studentService
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function show(Student $student, StudentService $studentService)
    {
        return view('showStudent', $studentService->show($student));
    }


    /**
     * Activate Student
     *
     * @param  Student $student
     * @return \Illuminate\Http\RedirectResponse
     */
    public function activate(Student $student)
    {
        $student->is_active = true;
        $student->save();

        return back()->with('success', 'Student Activated!');
    }

    public function deactivate(Student $student)
    {

        $student->is_active = false;
        $student->save();

        return back()->with('success', 'Student Deactivated!');
    }

    public function edit(Student $student)
    {
        $classrooms = Classroom::pluck('name')->all();
        return view('editStudent', compact(['student', 'classrooms']));
    }

    public function update(Student $student, UpdateStudentRequest $request)
    {
        $student->update($request->validated());
        return redirect(route('student.edit', ['student' => $student]))->with('success', 'Student Updated!');
    }

    public function getSessionalResults(Student $student, $academicSessionName, StudentService $studentService)
    {
        $sessionalResults = $studentService->getSessionalResults($student, $academicSessionName);
        return view('studentSessionalResults', $sessionalResults);
    }

    public function getTermResults(Student $student, $termSlug, $academicSessionName, StudentService $studentService)
    {
        $termResults = $studentService->getTermResults($student, $termSlug, $academicSessionName);

        return view('studentTermResults', $termResults);
    }

    public function destroy(Student $student)
    {
        $this->authorize('delete', $student);

        $student->delete();

        return back()->with('success', 'Student deleted');
    }

    public function forceDelete($id, Student $student)
    {
        $this->authorize('delete', $student);

        $student = Student::withTrashed()->findOrFail($id);
        $guardian = $student->guardian;
        $guardianChildren = $guardian->children()->withTrashed()->get();

        //delete student image if it exists
        if (!is_null($student->image)) {
            $deletePath = $student->image;
            $deletePath = str_replace('storage/', '', $deletePath);
            $deletePath = 'public/' . $deletePath;

            Storage::delete($deletePath);
        }

        /**if guardian has more than one child delete only the student's 
         * data else delete the student and the guargian's data
         */
        if (count($guardianChildren) > 1) {
            $student->forceDelete();
        } else {
            $student->forceDelete();
            $guardian->delete();
        }

        return back()->with('success', 'Student deleted permanently');
    }

    public function uploadImage(Student $student, Request $request, StudentService $studentService)
    {
        $studentService->uploadImage($student, $request);
        return back()->with('success', 'Image uploaded successfully');
    }

    public function showStudentSettingsView(Student $student)
    {
        $currentAcademicSession = Period::activePeriod()->academicSession;

        if (is_null($currentAcademicSession)) {
            return back()->with('error', 'Current Academic Session is not set');
        }

        $pdTypes = PDType::all();
        $terms = Term::all();

        return view('studentSettings', compact('student', 'pdTypes', 'currentAcademicSession', 'terms'));
    }

    public function promote(Student $student)
    {

        $classRank = $student->classroom->rank;
        $highestClassRank = Classroom::max('rank');

        if ($classRank !== $highestClassRank) {
            $newClassRank = $classRank + 1;
            $newClassId = Classroom::where('rank', $newClassRank)->first()->id;
            $student->classroom_id = $newClassId;
            $student->save();

            return back()->with('success', 'Student Promoted!');
        }

        return back()->with('error', 'Student is in the Maximum class possible');
    }

    public function demote(Student $student)
    {

        $classRank = $student->classroom->rank;
        $lowestClassRank = Classroom::min('rank');

        //if the student is not in the lowest class then demote the student
        if ($classRank !== $lowestClassRank) {
            $newClassRank = $classRank - 1;
            $newClassId = Classroom::where('rank', $newClassRank)->first()->id;
            $student->classroom_id = $newClassId;

            // if student has graduated, 'ungraduate' the student
            if (!is_null($student->graduated_at)) {
                $student->graduated_at = null;
            }

            $student->save();

            return back()->with('success', 'Student Demoted!');
        }

        return back()->with('error', 'Student is in the Lowest class possible');
    }

    public function showTrashed()
    {
        $students = Student::onlyTrashed()->get();

        return view('studentTrash', compact('students'));
    }

    public function restore($id)
    {
        $student = Student::withTrashed()->findOrFail($id);
        $student->restore();

        return back()->with('success', 'Student restored!');
    }

    public function graduate(Student $student)
    {
        $student->graduated_at = now();
        $student->is_active = false;
        $student->save();

        return back()->with('success', 'Student Graduated!');
    }
}
