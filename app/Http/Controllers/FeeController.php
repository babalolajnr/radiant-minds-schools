<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Fee;
use App\Models\Period;
use Illuminate\Http\Request;

class FeeController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'classroom' => ['required', 'string', 'exists:classrooms,name'],
            'fee' => ['required', 'string', 'numeric'],
            'period' => ['required', 'string', 'exists:periods,slug']
        ]);

        $classroom = Classroom::where('name', $data['classroom'])->first();
        $period = Period::where('slug', $data['period'])->first();

        //check if record exists
        $row = Fee::where('classroom_id', $classroom->id)->where('period_id', $period->id);

        if ($row->exists()){
            return back()->with('error', 'Record Exists');
        }

        Fee::create([
            'classroom_id' => $classroom->id,
            'fee' => $data['fee'],
            'period_id' => $period->id,
        ]);

        return back()->with('success', 'Record created');
    }
}
