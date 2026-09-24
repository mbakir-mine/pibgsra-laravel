<?php

namespace App\Http\Controllers;

use App\Models\Family;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FamilyController extends Controller
{
    public function index(Request $request)
    {
        return view('families.index', [
            'families' => Family::with('school')
                ->whereIn('school_id', $request->user()->accessibleSchoolIds())
                ->latest()
                ->paginate(15),
        ]);
    }

    public function create(Request $request)
    {
        return view('families.create', [
            'schools' => $request->user()->accessibleSchoolsQuery()->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $schoolIds = $request->user()->accessibleSchoolIds()->all();

        $data = $request->validate([
            'school_id' => ['required', Rule::in($schoolIds)],
            'family_code' => 'required|string|max:50',
            'name' => 'required|string|max:255',
        ]);

        Family::create($data);

        return redirect()->route('families.index')->with('status', 'Keluarga berjaya ditambah.');
    }
}
