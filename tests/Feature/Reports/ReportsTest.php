<?php

namespace Tests\Feature\Reports;

use Tests\TestCase;
// use Barryvdh\Snappy\Facades\SnappyPdf as PDF;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use PDF;

class ReportsTest extends TestCase
{
    use RefreshDatabase;

    private function user(): User
    {
        return User::factory()->create(['role' => User::USER]);
    }
    
    // PDF Reports
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
}
