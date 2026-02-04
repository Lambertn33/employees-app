<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Http\Resources\EmployeeResource;
use App\Services\EmployeeServices;
use App\Http\Requests\Employees\StoreRequest as EmployeesStoreRequest;

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
        $this->authorize('viewAny', Employee::class);

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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
