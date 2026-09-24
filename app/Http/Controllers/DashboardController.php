<?php
namespace App\Http\Controllers;

use App\Models\Family;
use App\Models\Payment;
use App\Models\School;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $schoolIds = Auth::user()->accessibleSchoolIds();

        return view('dashboard', [
            'schools' => School::whereIn('id', $schoolIds)->count(),
            'families' => Family::whereIn('school_id', $schoolIds)->count(),
            'students' => Student::whereIn('school_id', $schoolIds)->where('is_active', true)->count(),
            'payments' => Payment::whereIn('school_id', $schoolIds)->where('status', 'SUCCESS')->sum('amount'),
        ]);
    }
}
