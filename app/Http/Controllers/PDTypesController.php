<?php

namespace App\Http\Controllers;

use App\Models\PDType;
use Illuminate\Http\Request;

class PDTypesController extends Controller
{
    public function index()
    {
        $pdTypes = PDType::all();
        return view('pdTypes', compact('pdTypes'));
    }
}
