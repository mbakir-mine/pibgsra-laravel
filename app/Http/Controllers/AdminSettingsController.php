<?php

namespace App\Http\Controllers;

use App\Models\Receipt;
use App\Models\ReceiptSequence;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminSettingsController extends Controller
{
    private function owner(Request $request): void
    {
        abort_unless($request->user()->isOwner(), 403, 'Hanya Owner boleh mengurus tetapan admin.');
    }

    public function index(Request $request)
    {
        $this->owner($request);

        return view('admin-settings', ['users' => User::with('roles')->orderBy('name')->get()]);
    }

    public function resetReceipts(Request $request)
    {
        $this->owner($request);
        $request->validate(['confirmation' => ['required', 'in:PADAM RESIT']]);
        $year = now()->year;

        DB::transaction(function () use ($year) {
            Receipt::query()->delete();
            ReceiptSequence::where('receipt_year', $year)->update(['last_number' => 0]);
        });

        return back()->with('status', "Semua rekod resit dipadam dan nombor resit tahun {$year} dimulakan semula.");
    }

    public function destroyUser(Request $request, User $user)
    {
        $this->owner($request);
        abort_if($user->is($request->user()), 422, 'Owner tidak boleh memadam akaun sendiri.');
        abort_if($user->isOwner() && User::whereHas('roles', fn ($q) => $q->where('role', UserRole::OWNER))->count() <= 1, 422, 'Owner terakhir tidak boleh dipadam.');

        $user->delete();

        return back()->with('status', 'Pengguna berjaya dipadam.');
    }
}
