<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Http\Resources\EmployeeResource;
use App\Services\EmployeeServices;
use App\Http\Requests\Employees\StoreRequest as EmployeesStoreRequest;
use App\Http\Requests\Employees\UpdateRequest as EmployeesUpdateRequest;

class EmployeesController extends Controller
{
    public function __construct(private EmployeeServices $employeeServices)
    {
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->employeeServices->getEmployees($request);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EmployeesStoreRequest $request)
    {
        $this->authorize('create', Employee::class);

        $employee = $this->employeeServices->createEmployee($request->validated());

        return new EmployeeResource($employee);
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        return new EmployeeResource($employee);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EmployeesUpdateRequest $request, Employee $employee)
    {
        $this->authorize('update', $employee);

        $this->employeeServices->updateEmployee($request->validated(), $employee);

        return new EmployeeResource($employee);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        $this->authorize('delete', $employee);

        $employee->delete();

        return response()->json(['message' => 'Employee deleted']);
    }
}
