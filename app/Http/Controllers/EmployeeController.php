<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeStoreRequest;
use App\Http\Requests\EmployeeUpdateRequest;
use App\Http\Resources\EmployeeCollection;
use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EmployeeController extends Controller
{
    public function index(Request $request): EmployeeCollection
    {
        $employees = Employee::with('department')->latest()->get();

        return new EmployeeCollection($employees);
    }

    public function store(EmployeeStoreRequest $request): EmployeeResource
    {
        $employee = Employee::create($request->validated());
        $employee->save();
        return new EmployeeResource($employee);
    }

    public function show(Request $request, Employee $employee): EmployeeResource
    {
        return new EmployeeResource($employee);
    }

    public function update(EmployeeUpdateRequest $request, Employee $employee): EmployeeResource
    {
        $employee->update($request->validated());
        // $employee->save();
        return new EmployeeResource($employee);
    }

    public function destroy(Request $request, Employee $employee): Response
    {
        $employee->delete();

        return response()->noContent();
    }
}
