<?php

namespace App\Http\Controllers;

use App\Models\CaseFile;

class CaseController extends Controller
{
    public function show($caseId)
    {
        $case = CaseFile::findOrFail($caseId);
        return view('cases.show', compact('case'));
    }

}
