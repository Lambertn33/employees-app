<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use App\Policies\AttendancePolicy;

#[UsePolicy(AttendancePolicy::class)]
class Attendance extends Model
{
    protected $fillable = [
        'employee_id', 'date', 'arrived_at', 'left_at'
    ];

    protected $casts = [
        'arrived_at' => 'datetime',
        'left_at' => 'datetime'
    ];

    /**
     * Get the employee that owns the Attendance
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }
}
