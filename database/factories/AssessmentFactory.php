<?php

namespace Database\Factories;

use App\Models\AcademicSession;
use App\Models\Assessment;
use App\Models\AssessmentType;
use App\Models\Term;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;

class AssessmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Assessment::class;
    private function generateValues()
    {
        $term = Term::pluck('id')->all();
        $assessmentType = AssessmentType::pluck('id')->all();
        $academicSession = AcademicSession::pluck('id')->all();

        if (!empty($assessmentType)) {
            $assessmentType = Arr::random($assessmentType);
        } else {
            Artisan::call('db:seed', ['--class' => 'AssessmentTypeSeeder']);
            $assessmentType = AssessmentType::pluck('id')->all();
            $assessmentType = Arr::random($assessmentType);
        }

        if (!empty($term)) {
            $term = Arr::random($term);
        } else {
            Artisan::call('db:seed', ['--class' => 'TermSeeder']);
            $term = Term::pluck('id')->all();
            $term = Arr::random($term);
        }

        if (!empty($academicSession)) {
            $academicSession = Arr::random($academicSession);
        } else {
            Artisan::call('db:seed', ['--class' => 'AcademicSessionSeeder']);
            $academicSession = AcademicSession::pluck('id')->all();
            $academicSession = Arr::random($academicSession);
        }

        $assessmentTypeName = AssessmentType::find($assessmentType)->name;
        $termName = Term::find($term)->name;
        $academicSessionName = AcademicSession::find($academicSession)->name;

        return [
            'term' => $term,
            'academicSession' => $academicSession,
            'assessmentType' => $assessmentType,
            'assessmentTypeName' => $assessmentTypeName,
            'termName'  => $termName,
            'academicSessionName' => $academicSessionName
        ];
    }

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {


        $values = $this->generateValues();
        $name = $values['assessmentTypeName'] . ' ' . $values['termName'] . ' ' . $values['academicSessionName'];
        $assessment = Assessment::where('name', $name);
        
        while ($assessment->exists()) {
            $values = $this->generateValues();
            $name = $values['assessmentTypeName'] . ' ' . $values['termName'] . ' ' . $values['academicSessionName'];
            $assessment = Assessment::where('name', $name);
        }

        return [
            'term_id' => $values['term'],
            'assessment_type_id' => $values['assessmentType'],
            'academic_session_id' => $values['academicSession'],
            'name' => $name
        ];
    }
}
