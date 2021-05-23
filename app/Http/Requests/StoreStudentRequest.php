<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreStudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (Auth::guard('web')->check()) {
            return true;
        }

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return [
            'first_name' => ['required', 'string', 'max:30'],
            'last_name' => ['required', 'string', 'max:30'],
            'sex' => ['required', 'string'],
            'admission_no' => ['required', 'string', Rule::unique('students')],
            'lg' => ['required', 'string'],
            'state' => ['required', 'string'],
            'country' => ['required', 'string'],
            'blood_group' => ['required', 'string'],
            'date_of_birth' => ['required', 'date', 'before:' . now()],
            'place_of_birth' => ['required'],
            'classroom' => ['required', 'string'],
            'guardian_title' => ['required', 'max:30', 'string'],
            'guardian_first_name' => ['required', 'max:30', 'string'],
            'guardian_last_name' => ['required', 'max:30', 'string'],
            'guardian_email' => ['required', 'string', 'email:rfc,dns'],
            'guardian_phone' => ['required', 'string', 'between:10,15'],
            'guardian_occupation' => ['required', 'string'],
            'guardian_address' => ['required']
        ];
    }
}
