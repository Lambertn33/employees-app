<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\hasMany;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use App\Policies\EmployeePolicy;

#[UsePolicy(EmployeePolicy::class)]
class Employee extends Model
{
    protected $fillable = [
        'names', 'email', 'code', 'telephone'
    ];

    /**
     * Get all of the attendances for the Employee
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'employee_id', 'id');
    }
}
