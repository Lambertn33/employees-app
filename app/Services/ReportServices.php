<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Support\Facades\Date;
use Barryvdh\Snappy\Facades\SnappyPdf;
use App\Exports\AttendancesExport;
use Maatwebsite\Excel\Facades\Excel;

class ReportServices

{
    public function getDailyReportInPdf(?string $date = null): array
    {
        $day = $date ? Carbon::parse($date)->startOfDay() : now()->startOfDay();
        $from = $day->copy()->startOfDay();
        $to   = $day->copy()->endOfDay();

        $attendances = Attendance::query()
            ->with('employee')
            ->whereBetween('arrived_at', [$from, $to])
            ->orderBy('arrived_at')
            ->get();

        $employees = Employee::query()
            ->orderBy('names')
            ->get()
            ->map(function ($employee) use ($attendances) {
                $todayAttendance = $attendances->firstWhere('employee_id', $employee->id);

                return [
                    'code'       => $employee->code,
                    'names'      => $employee->names,
                    'email'      => $employee->email,
                    'telephone'  => $employee->telephone,
                    'arrived_at' => $todayAttendance?->arrived_at,
                    'left_at'    => $todayAttendance?->left_at,
                ];
            });

        $payload = [
            'date'         => $day->toDateString(),
            'generated_at' => now()->toDateTimeString(),
            'rows'         => $employees,
        ];

        $pdf = SnappyPdf::loadView('reports.pdf', $payload)
            ->setPaper('a4')
            ->setOrientation('portrait')
            ->setOption('margin-top', '10mm')
            ->setOption('margin-bottom', '10mm')
            ->setOption('margin-left', '10mm')
            ->setOption('margin-right', '10mm');

        $filename = 'attendance-' . $day->toDateString() . '.pdf';

        return [
            'filename' => $filename,
            'content'  => $pdf->output(),
        ];
    }

    public function getDailyReportInExcel(?string $date = null)
    {
        $day = $date ?Carbon::parse($date)->startOfDay() : now()->startOfDay();
        $from = $day->copy()->startOfDay();
        $to = $day->copy()->endOfDay();
    
        $attendances = Attendance::query()
            ->with(['employee'])
            ->whereBetween('arrived_at', [$from, $to])
            ->orderBy('arrived_at')
            ->get();
            
        $rows = Employee::query()
            ->orderBy('names')
            ->get()
            ->values()
            ->map(function ($employee, $idx) use ($attendances) {
                $todayAttendance = $attendances->firstWhere('employee_id', $employee->id);
    
                return [
                    'index' => $idx + 1,
                    'code' => $employee->code,
                    'names' => $employee->names,
                    'email' => $employee->email,
                    'telephone' => $employee->telephone,
                    'arrived_at' => optional($todayAttendance?->arrived_at)?->toDateTimeString(),
                    'left_at' => optional($todayAttendance?->left_at)?->toDateTimeString(),
                ];
            });
        $filename = 'attendance-'.$day->toDateString().'.xlsx';

        return Excel::download(new AttendancesExport($rows), $filename);
    }
}