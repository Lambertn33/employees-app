<?php

namespace Tests\Feature\Reports;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use PDF;
use App\Exports\AttendancesExport;
use Maatwebsite\Excel\Facades\Excel;


class ReportsTest extends TestCase
{
    use RefreshDatabase;

    private function user(): User
    {
        return User::factory()->create(['role' => User::USER]);
    }
    
    public function test_authenticated_user_can_generate_pdf_report(): void
    {
        PDF::fake();

        $user = $this->user();

        $this->actingAs($user, 'sanctum')
            ->get('/api/reports/pdf')
            ->assertOk();

        PDF::assertViewIs('reports.pdf');
        PDF::assertSeeText('Daily Attendance Report');
    }

    public function test_authenticated_user_can_download_excel_report(): void
    {
        $user = $this->user();

        $day = now()->startOfDay();
        $filename = 'attendance-'.$day->toDateString().'.xlsx';

        $res = $this->actingAs($user, 'sanctum')
            ->get('/api/reports/excel');

        $res->assertOk();

        $res->assertHeader(
            'content-disposition',
            'attachment; filename='.$filename
        );

        $this->assertStringContainsString(
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            $res->headers->get('content-type')
        );
    }
}
