<?php

namespace App\Http\Controllers;

use App\Models\FeeCategory;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FeeCategoryController extends Controller
{
    public function index(Request $request)
    {
        $schools = $request->user()
            ->accessibleSchoolsQuery()
            ->orderBy('district')
            ->orderBy('name')
            ->get();

        $schools->each(function (School $school) {
            $category = strtoupper((string) $school->category);
            $name = strtoupper($school->name);
            $code = strtoupper($school->code);

            if (! in_array($category, ['SRA', 'SRAI'], true)) {
                $category = str_starts_with($code, 'BYR') || str_starts_with($name, 'SRAI') || str_contains($name, 'INTEGRASI')
                    ? 'SRAI'
                    : 'SRA';
            }

            $school->setAttribute('display_category', $category);
        });

        return view('fees.index', [
            'schools' => $schools,
            'districts' => $schools->groupBy(fn (School $school) => $school->district ?: 'Tanpa daerah'),
            'sraCount' => $schools->where('display_category', 'SRA')->count(),
            'sraiCount' => $schools->where('display_category', 'SRAI')->count(),
        ]);
    }

    public function create(Request $request)
    {
        return view('fees.create', [
            'schools' => $request->user()->accessibleSchoolsQuery()->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $schoolIds = $request->user()->accessibleSchoolIds()->all();

        $data = $request->validate([
            'school_id' => ['required', Rule::in($schoolIds)],
            'code' => 'required|string|max:50',
            'name' => 'required|string|max:255',
            'applies_to' => 'required|in:FAMILY,STUDENT',
        ]);

        FeeCategory::create($data);

        return redirect()->route('fees.index')->with('status', 'Kategori yuran berjaya ditambah.');
    }
}
