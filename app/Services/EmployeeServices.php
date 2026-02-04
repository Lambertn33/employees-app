<?php

namespace App\Services;

use App\Models\Employee;
use Carbon\Carbon;
use App\Http\Resources\EmployeeResource;
use Illuminate\Http\Request;

class EmployeeServices
{
    public function getEmployees(Request $request)
    {
        $perPage = (int) $request->query('per_page', 10);
    
        $perPage = min(max($perPage, 1), 100);
    
        $employees = Employee::query()
            ->latest()
            ->paginate($perPage);
    
        return EmployeeResource::collection($employees);
    }
    
    public function createEmployee(array $data)
    {
        $data['code'] = $this->generateEmployeeCode();

        return Employee::create($data);
    }

    public function generateEmployeeCode()
    {
        return 'EMP-' . Carbon::now()->format('YmdHis');
    }
}