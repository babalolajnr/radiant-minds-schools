<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Fee;
use App\Models\Period;
use Illuminate\Http\Request;

class FeeController extends Controller
{
    /**
     * store fee
     *
     * @param  Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'classroom' => ['required', 'string', 'exists:classrooms,name'],
            'amount' => ['required', 'string', 'numeric'],
            'period' => ['required', 'string', 'exists:periods,slug']
        ]);

        $classroom = Classroom::where('name', $data['classroom'])->first();
        $period = Period::where('slug', $data['period'])->first();

        //check if record exists
        $row = Fee::where('classroom_id', $classroom->id)->where('period_id', $period->id);

        if ($row->exists()) {
            return back()->with('error', 'Record Exists');
        }

        Fee::create([
            'classroom_id' => $classroom->id,
            'amount' => $data['amount'],
            'period_id' => $period->id,
        ]);

        return back()->with('success', 'Record created');
    }

    /**
     * show edit fee page
     *
     * @param  Fee $fee
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function edit(Fee $fee)
    {
        return view('editFee', compact('fee'));
    }

    /**
     * update fee
     *
     * @param  Request $request
     * @param  Fee $fee
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Fee $fee)
    {
        $data = $request->validate([
            'amount' => ['required', 'string', 'numeric'],
        ]);

        $fee->update($data);

        return back()->with('success', 'Record Updated');
    }

    /**
     * destroy fee record
     *
     * @param  Fee $fee
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Fee $fee)
    {
        try {
            $fee->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == 23000) {
                //SQLSTATE[23000]: Integrity constraint violation
                return back()->with('error', 'Fee cannot be deleted because some resources are dependent on it!');
            }
        }
        return back()->with('success', 'Deleted!');
    }
}
