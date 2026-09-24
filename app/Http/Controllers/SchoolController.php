<?php

namespace App\Http\Controllers;

use App\Models\School;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    public function index(Request $request)
    {
        $schools = $request->user()
            ->accessibleSchoolsQuery()
            ->withCount(['families', 'students'])
            ->latest()
            ->paginate(15);

        return view('schools.index', ['schools' => $schools]);
    }

    public function create()
    {
        return view('schools.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50',
            'district' => 'nullable|string|max:120',
            'category' => 'nullable|string|max:20',
        ]);

        School::create($data);

        return redirect()->route('schools.index')->with('status', 'Sekolah berjaya ditambah.');
    }
}
