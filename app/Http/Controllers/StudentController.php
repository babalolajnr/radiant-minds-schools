<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Guardian;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
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
            'date_of_birth' => ['required', 'date'],
            'classroom' => ['required', 'string'],
            'guardian' => ['string'],
            'guardian_first_name' => ['string', 'max:30', Rule::requiredIf(function () use ($request) {
                return $request->guardian == null;
            })],
            'guardian_last_name' => ['string', 'max:30', Rule::requiredIf(function () use ($request) {
                return $request->guardian_first_name != null;
            })],
            'guardian_email' => [Rule::requiredIf(function () use ($request) {
                return $request->guardian_first_name != null;
            }), 'string', 'unique:guardians,email', 'email:rfc,dns'],
            'guardian_phone' => [Rule::requiredIf(function () use ($request) {
                return $request->guardian_first_name != null;
            }), 'string', 'unique:guardians,phone', 'max:15', 'min:10'],
        ]);


        $classroom =  Classroom::where('name', $request->classroom)->first();
        /**
         * if request has guradian_first_name
         */
        if ($request->guardian_first_name) {

            $fullname = $request->guardian_first_name . ' ' . $request->guardian_last_name . ' ' . Str::random(5);
            $slug = Str::of($fullname)->slug('-');
            $guardian = Guardian::create([
                'first_name' => $request->guardian_first_name,
                'last_name' => $request->guardian_last_name,
                'email' => $request->guardian_email,
                'phone' => $request->guardian_phone,
                'slug' => $slug,
            ]);

            Student::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'sex' => $request->sex,
                'admission_no' => $request->admission_no,
                'lg' => $request->lg,
                'state' => $request->state,
                'country' => $request->country,
                'date_of_birth' => $request->date_of_birth,
                'guardian_id' => $guardian->id,
                'classroom_id' => $classroom->id,
                'status' => 'active',
            ]);
        } else {
            $guardian = Guardian::where('slug', $request->guardian)->first();

            Student::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'sex' => $request->sex,
                'admission_no' => $request->admission_no,
                'lg' => $request->lg,
                'state' => $request->state,
                'country' => $request->country,
                'date_of_birth' => $request->date_of_birth,
                'guardian_id' => $guardian->id,
                'classroom_id' => $classroom->id,
                'status' => 'active',
            ]);
        }

        return response(200);
    }
}
