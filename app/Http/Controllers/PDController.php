<?php

namespace App\Http\Controllers;

use App\Models\PDTypes;
use App\Models\Student;
use App\Models\Term;
use Illuminate\Http\Request;

class PDController extends Controller
{
    public function create($id)
    {
        $student = Student::findOrFail($id);
        $pdTypes = PDTypes::all();
        $terms = Term::all();
        return view('createPD', compact('pdTypes', 'student', 'terms'));
    }

    public function store($id, Request $request, $termId)
    {
        
    }
}
