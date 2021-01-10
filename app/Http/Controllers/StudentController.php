<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Guardian;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::all();
        return response(200);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Student::class);

        $this->validate($request, [
            'first_name' => ['required', 'string', 'max:30'],
            'last_name' => ['required', 'string', 'max:30'],
            'sex' => ['required', 'string'],
            'admission_no' => ['required', 'string', 'unique:students'],
            'lg' => ['required', 'string'],
            'state' => ['required', 'string'],
            'country' => ['required', 'string'],
            'blood_group' => ['required', 'string'],
            'date_of_birth' => ['required', 'date'],
            'place_of_birth' => ['required'],
            'classroom' => ['required', 'string'],
            'guardian_title' => ['string', 'max:30', 'required'],
            'guardian_first_name' => ['string', 'max:30', 'required'],
            'guardian_last_name' => ['string', 'max:30', 'required'],
            'guardian_email' => ['required', 'string', 'email:rfc,dns'],
            'guardian_phone' => ['required', 'string', 'max:15', 'min:10'],
            'guardian_occupation' => ['required', 'string'],
            'guardian_address' => ['required']
        ]);


        $classroom =  Classroom::where('name', $request->classroom)->first();

        $studentInfo = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'sex' => $request->sex,
            'admission_no' => $request->admission_no,
            'lg' => $request->lg,
            'state' => $request->state,
            'country' => $request->country,
            'blood_group' => $request->blood_group,
            'date_of_birth' => $request->date_of_birth,
            'place_of_birth' => $request->place_of_birth,
            'classroom_id' => $classroom->id,
            'status' => 'active'
        ];

        $guardian = Guardian::where('phone', $request->guardian_phone)->first();

        if (is_null($guardian)) {
            $guardian = Guardian::create([
                'title' => $request->guardian_title,
                'first_name' => $request->guardian_first_name,
                'last_name' => $request->guardian_last_name,
                'email' => $request->guardian_email,
                'phone' => $request->guardian_phone,
                'occupation' => $request->guardian_occupation,
                'address' => $request->guardian_address,
            ]);
        }

        //assign guardian_id to an array and merge it with the original student info array
        $guardianID = ['guardian_id' => $guardian->id];
        $studentInfo = array_merge($studentInfo, $guardianID);

        Student::create($studentInfo);
    }
}
