<?php

namespace App\Http\Controllers;

use App\Models\Family;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        return view('students.index', [
            'students' => Student::with(['school', 'family'])
                ->whereIn('school_id', $request->user()->accessibleSchoolIds())
                ->latest()
                ->paginate(15),
        ]);
    }

    public function create(Request $request)
    {
        $schoolIds = $request->user()->accessibleSchoolIds();

        return view('students.create', [
            'schools' => $request->user()->accessibleSchoolsQuery()->orderBy('name')->get(),
            'families' => Family::whereIn('school_id', $schoolIds)->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $schoolIds = $request->user()->accessibleSchoolIds()->all();

        $data = $request->validate([
            'school_id' => ['required', Rule::in($schoolIds)],
            'family_id' => 'required|exists:families,id',
            'full_name' => 'required|string|max:255',
            'student_identifier' => 'required|string|max:80',
            'year_level' => 'required|integer|min:1|max:6',
        ]);

        Student::create($data + ['is_active' => true]);

        return redirect()->route('students.index')->with('status', 'Murid berjaya ditambah.');
    }
}
