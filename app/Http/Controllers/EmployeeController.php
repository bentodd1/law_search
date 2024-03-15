<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Method to display a list of employees or the initial employee page
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Method to show a form to create a new employee
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Method to store a new employee
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee)
    {
        // Method to show edit form for an employee
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        // Method to update an employee
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        // Method to delete an employee
    }

    /**
     * Search for a resource in storage.
     */
    public function search(Request $request)
    {
        $searchResults = null;

        if ($request->has('query')) {
            $searchQuery = $request->input('query');

            $searchResults = Employee::search($searchQuery)->get();
        }
        return view('employee-search', compact('searchResults'));
    }

    public function show(Employee $employee)
    {
        $cases = $employee->caseFileHeaders;
        $caseCount = $cases->count();

        return view('employees.show', compact('employee', 'cases', 'caseCount'));
    }
}

