<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTeacherRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
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
            'email' => ['required', 'string', 'unique:teachers', 'email:rfc,dns'],
            'phone' => ['required', 'string', 'unique:teachers', 'max:15', 'min:10'],
            'date_of_birth' => ['required', 'date', 'before:' . now()],
            'sex' => ['required', 'string'],
        ];
    }
}
