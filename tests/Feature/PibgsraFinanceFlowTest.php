<?php

namespace Tests\Feature;

use App\Models\AcademicSession;
use App\Models\Family;
use App\Models\FamilyFeeCharge;
use App\Models\FeeCategory;
use App\Models\Receipt;
use App\Models\School;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PibgsraFinanceFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_family_payment_allocates_charge_issues_receipt_and_writes_audit(): void
    {
        $user = User::factory()->create();
        $school = School::create(['name' => 'Sekolah Ujian', 'code' => 'TEST-01']);
        UserRole::create(['user_id' => $user->id, 'role' => UserRole::OWNER, 'state' => 'Selangor']);
        $family = Family::create(['school_id' => $school->id, 'family_code' => 'F-001', 'name' => 'Keluarga Ujian']);
        $session = AcademicSession::create(['school_id' => $school->id, 'name' => '2026', 'starts_on' => '2026-01-01', 'ends_on' => '2026-12-31']);
        $category = FeeCategory::create(['school_id' => $school->id, 'code' => 'YURAN', 'name' => 'Yuran PIBG', 'applies_to' => 'FAMILY']);
        $charge = FamilyFeeCharge::create(['school_id' => $school->id, 'family_id' => $family->id, 'academic_session_id' => $session->id, 'fee_category_id' => $category->id, 'amount' => 100, 'paid_amount' => 0, 'balance_amount' => 100, 'due_date' => '2026-01-31', 'status' => 'DUE']);

        $response = $this->actingAs($user)->post(route('payments.store'), [
            'school_id' => $school->id,
            'family_id' => $family->id,
            'amount' => 60,
            'method' => 'MANUAL',
        ]);

        $response->assertRedirect(route('payments.index'));
        $this->assertDatabaseHas('payments', ['family_id' => $family->id, 'status' => 'SUCCESS', 'amount' => 60]);
        $this->assertDatabaseHas('payment_allocations', ['family_fee_charge_id' => $charge->id, 'amount' => 60]);
        $this->assertDatabaseHas('family_fee_charges', ['id' => $charge->id, 'paid_amount' => 60, 'balance_amount' => 40, 'status' => 'PARTIAL']);
        $this->assertDatabaseCount('receipts', 1);
        $this->assertDatabaseHas('audit_logs', ['action' => 'created']);
        $this->assertNotEmpty(Receipt::first()->receipt_number);
    }
}
