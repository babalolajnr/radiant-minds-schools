<?php

namespace App\Http\Requests;

use App\Models\Period;
use App\Traits\ValidationTrait;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePeriodRequest extends FormRequest
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
        $period = $this->route('period');

        return [
            'start_date' => ['required', 'date', Rule::unique('periods')->ignore($period), "after_or_equal:{$period->academicSession->start_date}"],
            'end_date' => ['required', 'date', 'after:start_date', Rule::unique('periods')->ignore($period), "before_or_equal:{$period->academicSession->end_date}"],
            'no_times_school_opened' => ['numeric', 'nullable']
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
            $validateDateRange = $this->validateDateRange($this->start_date, $this->end_date, Period::class, $this->route('period'));

            if ($validateDateRange !== true) {

                $validator->errors()->add('start_date', 'Date range overlaps with another period');
                $validator->errors()->add('end_date', 'Date range overlaps with another period');
            }
        });
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
}
