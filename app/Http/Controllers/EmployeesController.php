<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Http\Resources\EmployeeResource;
use App\Services\EmployeeServices;
use App\Http\Requests\Employees\StoreRequest as EmployeesStoreRequest;
use App\Http\Requests\Employees\UpdateRequest as EmployeesUpdateRequest;
use OpenApi\Attributes as OA;

class EmployeesController extends Controller
{
    public function __construct(private EmployeeServices $employeeServices)
    {
    }
    /**
     * Display a listing of the resource.
     */
    #[OA\Get(
        path: '/api/employees',
        operationId: 'employeesIndex',
        tags: ['Employees'],
        security: [['sanctum' => []]],
        summary: 'List employees (paginated)',
        parameters: [
            new OA\QueryParameter(
                name: 'per_page',
                required: false,
                description: 'Items per page (1..100), default 10',
                schema: new OA\Schema(type: 'integer', example: 10)
            ),
            new OA\QueryParameter(
                name: 'page',
                required: false,
                description: 'Page number',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Paginated employees',
                content: new OA\JsonContent(ref: '#/components/schemas/PaginatedEmployees')
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]
    public function index(Request $request)
    {
        return $this->employeeServices->getEmployees($request);
    }

    /**
     * Store a newly created resource in storage.
     */
    #[OA\Post(
        path: '/api/employees',
        operationId: 'employeesStore',
        tags: ['Employees'],
        security: [['sanctum' => []]],
        summary: 'Create an employee (admin only)',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/EmployeeCreateRequest')
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Created',
                content: new OA\JsonContent(ref: '#/components/schemas/Employee')
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(
                response: 422,
                description: 'Validation error',
                content: new OA\JsonContent(ref: '#/components/schemas/ValidationError')
            ),
        ]
    )]
    public function store(EmployeesStoreRequest $request)
    {
        $this->authorize('create', Employee::class);

        $employee = $this->employeeServices->createEmployee($request->validated());

        return new EmployeeResource($employee);
    }

    /**
     * Display the specified resource.
     */
    #[OA\Get(
        path: '/api/employees/{employee}',
        operationId: 'employeesShow',
        tags: ['Employees'],
        security: [['sanctum' => []]],
        summary: 'Get an employee',
        parameters: [
            new OA\PathParameter(
                name: 'employee',
                required: true,
                description: 'Employee ID',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Employee',
                content: new OA\JsonContent(ref: '#/components/schemas/Employee')
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'Not found'),
        ]
    )]
    public function show(Employee $employee)
    {
        return new EmployeeResource($employee);
    }

    /**
     * Update the specified resource in storage.
     */
    #[OA\Put(
        path: '/api/employees/{employee}',
        operationId: 'employeesUpdate',
        tags: ['Employees'],
        security: [['sanctum' => []]],
        summary: 'Update an employee (admin only)',
        parameters: [
            new OA\PathParameter(
                name: 'employee',
                required: true,
                description: 'Employee ID',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/EmployeeUpdateRequest')
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Updated',
                content: new OA\JsonContent(ref: '#/components/schemas/Employee')
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 404, description: 'Not found'),
            new OA\Response(
                response: 422,
                description: 'Validation error',
                content: new OA\JsonContent(ref: '#/components/schemas/ValidationError')
            ),
        ]
    )]
    public function update(EmployeesUpdateRequest $request, Employee $employee)
    {
        $this->authorize('update', $employee);

        $this->employeeServices->updateEmployee($request->validated(), $employee);

        return new EmployeeResource($employee);
    }

    /**
     * Remove the specified resource from storage.
     */
    #[OA\Delete(
        path: '/api/employees/{employee}',
        operationId: 'employeesDestroy',
        tags: ['Employees'],
        security: [['sanctum' => []]],
        summary: 'Delete an employee (admin only)',
        parameters: [
            new OA\PathParameter(
                name: 'employee',
                required: true,
                description: 'Employee ID',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(response: 204, description: 'Deleted'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 404, description: 'Not found'),
        ]
    )]
    public function destroy(Employee $employee)
    {
        $this->authorize('delete', $employee);

        $employee->delete();

        return response()->json(['message' => 'Employee deleted']);
    }
}
