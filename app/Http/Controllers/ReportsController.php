<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ReportServices;

class ReportsController extends Controller
{
    public function __construct(private ReportServices $reportServices) {}

    public function downloadPdf(Request $request)
    {
        $date = $request->query('date'); // YYYY-MM-DD or null

        $result = $this->reportServices->getDailyReportInPdf($date);

        return response($result['content'], 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="'.$result['filename'].'"',
        ]);
    }

    public function downloadExcel(Request $request)
    {
        $date = $request->query('date'); // YYYY-MM-DD or null
        return $this->reportServices->getDailyReportInExcel($date);
    }
}
