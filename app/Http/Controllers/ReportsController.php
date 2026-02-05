<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ReportServices;
use OpenApi\Attributes as OA;

class ReportsController extends Controller
{
    public function __construct(private ReportServices $reportServices) {}


    #[OA\Get(
        path: '/api/reports/pdf',
        tags: ['Reports'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(
                name: 'date',
                in: 'query',
                required: false,
                description: 'YYYY-MM-DD',
                schema: new OA\Schema(type: 'string', example: '2026-02-04')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'PDF file',
                content: new OA\MediaType(
                    mediaType: 'application/pdf',
                    schema: new OA\Schema(type: 'string', format: 'binary')
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]
    public function downloadPdf(Request $request)
    {
        $date = $request->query('date'); // YYYY-MM-DD or null

        $result = $this->reportServices->getDailyReportInPdf($date);

        return response($result['content'], 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="'.$result['filename'].'"',
        ]);
    }


    #[OA\Get(
        path: '/api/reports/excel',
        tags: ['Reports'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(
                name: 'date',
                in: 'query',
                required: false,
                description: 'YYYY-MM-DD',
                schema: new OA\Schema(type: 'string', example: '2026-02-04')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Excel file',
                content: new OA\MediaType(
                    mediaType: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    schema: new OA\Schema(type: 'string', format: 'binary')
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]
    public function downloadExcel(Request $request)
    {
        $date = $request->query('date'); // YYYY-MM-DD or null
        return $this->reportServices->getDailyReportInExcel($date);
    }
}
