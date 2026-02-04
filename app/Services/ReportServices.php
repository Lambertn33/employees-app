<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Support\Facades\Date;
use Barryvdh\Snappy\Facades\SnappyPdf;

class ReportServices

{
    public function getDailyReportInPdf(?string $date = null): array
    {
        $day = $date ? Carbon::parse($date)->startOfDay() : now()->startOfDay();
        $from = $day->copy()->startOfDay();
        $to = $day->copy()->endOfDay();

        $attendances = Attendance::query()
            ->with(['employee'])
            ->whereBetween('arrived_at', [$from, $to])
            ->orderBy('arrived_at')
            ->get();

        $employees = Employee::query()
            ->orderBy('names')
            ->get()
            ->map(function ($employee) use ($attendances) {
                $todayAttendance = $attendances->firstWhere('employee_id', $employee->id);

                return [
                    'code' => $employee->code,
                    'names' => $employee->names,
                    'email' => $employee->email,
                    'telephone' => $employee->telephone,
                    'arrived_at' => $todayAttendance?->arrived_at,
                    'left_at' => $todayAttendance?->left_at,
                ];
            });

        $payload = [
            'date' => $day->toDateString(),
            'generated_at' => now()->toDateTimeString(),
            'rows' => $employees,
        ];

        $html = view('reports.pdf', $payload)->render();

        $pdf = SnappyPdf::loadHTML($html)
            ->setPaper('a4')
            ->setOrientation('portrait')
            ->setOption('margin-top', '10mm')
            ->setOption('margin-bottom', '10mm')
            ->setOption('margin-left', '10mm')
            ->setOption('margin-right', '10mm');

        $filename = 'attendance-'.$day->toDateString().'.pdf';

        return [
            'filename' => $filename,
            'content' => $pdf->output(),
        ];
    }
}