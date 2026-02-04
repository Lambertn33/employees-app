<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;

class ReportsController extends Controller
{
    public function getPdfReports()
    {
        $pdf = PDF::loadView('reports.dataPdf');
        return $pdf->download('invoice.pdf');
    }
}
