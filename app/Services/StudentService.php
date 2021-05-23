<?php

namespace App\Services;

use App\Http\Requests\StoreStudentRequest;
use App\Models\Classroom;
use App\Models\Guardian;
use App\Models\Student;

class StudentService
{
    /**
     * store student
     *
     * This method works by collecting all the guardian and student info from the user and
     * making sure it's all filled out. Then it checks if the guardian's phone number is present
     * in the database. If it is then it gets the guardian's id and inserts it into the student's table
     * 
     * @param  mixed $storeStudentRequest
     * @return void
     */
    public function store(StoreStudentRequest $storeStudentRequest)
    {
        //merge guardian and student validation rules
        $validatedData = $storeStudentRequest->validated();

        $guardian = Guardian::where('phone', $validatedData['guardian_phone'])->first();

        //if guardian does not exist create new guardian
        if (is_null($guardian)) {
            $guardian = Guardian::create([
                'title' => $validatedData['guardian_title'],
                'first_name' => $validatedData['guardian_first_name'],
                'last_name' => $validatedData['guardian_last_name'],
                'email' => $validatedData['guardian_email'],
                'phone' => $validatedData['guardian_phone'],
                'occupation' => $validatedData['guardian_occupation'],
                'address' => $validatedData['guardian_address'],
            ]);
        }

        //merge guardian id with student info
        $studentInfo = array_merge($this->studentInfo($validatedData), ['guardian_id' => $guardian->id]);

        Student::create($studentInfo);
    }

    /**
     * @return array
     * @param mixed $validatedData
     * 
     * returns student info after it been extracted from
     * the validated data
     */
    private function studentInfo($validatedData)
    {
        $classroom =  Classroom::where('name', $validatedData['classroom'])->first();

        return [
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'sex' => $validatedData['sex'],
            'admission_no' => $validatedData['admission_no'],
            'lg' => $validatedData['lg'],
            'state' => $validatedData['state'],
            'country' => $validatedData['country'],
            'blood_group' => $validatedData['blood_group'],
            'date_of_birth' => $validatedData['date_of_birth'],
            'place_of_birth' => $validatedData['place_of_birth'],
            'classroom_id' => $classroom->id,
        ];
    }
}
