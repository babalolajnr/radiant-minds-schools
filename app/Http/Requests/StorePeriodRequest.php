<?php

namespace App\Http\Requests;

use App\Models\AcademicSession;
use App\Models\Period;
use App\Models\Term;
use App\Traits\ValidationTrait;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;

class StorePeriodRequest extends FormRequest
{
    use ValidationTrait;

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
        $academicSession = AcademicSession::where('name', $this->academic_session)->first();

        if ($academicSession == null) {
            throw ValidationException::withMessages(['academic_session' => 'Academic Session not found']);
        }

        return [
            'academic_session' => ['required', 'exists:academic_sessions,name', 'string'],
            'term' => ['required', 'string', 'exists:terms,name'],
            'start_date' => ['required', 'date', 'unique:periods,start_date', "after_or_equal:{$academicSession->start_date}"],
            'end_date' => ['required', 'date', 'after:start_date', 'unique:periods,end_date', "before_or_equal:{$academicSession->end_date}"],
            'no_times_school_opened' => ['numeric', 'nullable']
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'start_date.after_or_equal' => 'Start date must be after or equal to the start date of the selected academic session',
            'end_date.before_or_equal' => 'End date must be before or equal to the end date of the selected academic session'
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            /**
             * Additional validation for no of times school opened to ensure that
             * it is not above the date range
             */

            if ($this->filled('no_times_school_opened')) {

                $startDate = Carbon::createFromFormat('Y-m-d', $this->start_date);
                $endDate = Carbon::createFromFormat('Y-m-d', $this->end_date);

                $diffInDaysBetweenStartDateAndEndDate = $startDate->diffInDays($endDate);

                if ($this->no_times_school_opened > $diffInDaysBetweenStartDateAndEndDate) {
                    $validator->errors()->add('no_times_school_opened', 'No of times school opened cannot be greater than no of days in date range');
                }
            }

            //check if date range is unique
            $validateDateRange = $this->validateDateRange($this->start_date, $this->end_date, Period::class);

            if ($validateDateRange !== true) {

                $validator->errors()->add('start_date', 'Date range overlaps with another period');
                $validator->errors()->add('end_date', 'Date range overlaps with another period');
            }

            //check if academic session and term exist on the same row
            $academicSession = AcademicSession::where('name', $this->academic_session)->first();
            $term = Term::where('name', $this->term)->first();

            $row = Period::where('academic_session_id', $academicSession->id)->where('term_id', $term->id);

            if ($row->exists()) {
                $validator->errors()->add('academic_session', 'Record Exists');
                $validator->errors()->add('term', 'Record Exists');
            }
        });
    }
}
