<?php

namespace App\Http\Controllers;

use App\Models\FeeCategory;
use App\Models\FeeRate;
use App\Models\FeeStructure;
use App\Models\AcademicSession;
use App\Models\School;
use App\Models\Family;
use App\Models\FamilyFeeCharge;
use Illuminate\Support\Facades\DB;
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
        $school = $request->user()->accessibleSchoolsQuery()->findOrFail($request->integer('school_id'));
        $category = strtoupper((string) $school->category);
        if (! in_array($category, ['SRA', 'SRAI'], true)) {
            $category = str_contains(strtoupper($school->name), 'INTEGRASI') ? 'SRAI' : 'SRA';
        }
        $items = $category === 'SRAI'
            ? [['BAYARAN_TAMBAHAN','Bayaran tambahan',32],['KURIKULUM','Kurikulum',32],['KOKURIKULUM','Kokurikulum',32],['PUSAT_SUMBER','Pusat sumber',22],['KEBERSIHAN','Kebersihan / keceriaan',12],['INSURAN_TAKAFUL','Insuran takaful',2],['KH_SEK_REN','KH Sek. Ren.',6],['MAKMAL_SAINS','Makmal sains',6],['KEBAJIKAN','Kebajikan',6]]
            : [['BAYARAN_TAMBAHAN','Bayaran tambahan',18],['KURIKULUM','Kurikulum',18],['KOKURIKULUM','Ko-kurikulum',12],['KEBERSIHAN','Kebersihan / keceriaan',6],['KEBAJIKAN','Kebajikan',4]];
        return view('fees.create', [
            'school' => $school, 'category' => $category, 'items' => $items,
        ]);
    }

    public function store(Request $request)
    {
        $schoolIds = $request->user()->accessibleSchoolIds()->all();

        $data = $request->validate(['school_id'=>['required',Rule::in($schoolIds)],'items'=>['required','array','min:1'],'items.*.code'=>['required','string','max:50'],'items.*.name'=>['required','string','max:255'],'items.*.amount'=>['required','numeric','min:0']]);
        DB::transaction(function () use ($data) {
            $session = AcademicSession::firstOrCreate(['school_id'=>$data['school_id'],'is_active'=>true],['name'=>'Tahun Semasa','starts_on'=>now()->startOfYear()->toDateString(),'ends_on'=>now()->endOfYear()->toDateString()]);
            $structure = FeeStructure::updateOrCreate(['school_id'=>$data['school_id'],'academic_session_id'=>$session->id],['name'=>'Pakej A JAIS','status'=>'ACTIVE','approved_annual_limit'=>collect($data['items'])->sum('amount')]);
            $rates = [];
            foreach ($data['items'] as $item) {
                $category = FeeCategory::updateOrCreate(['school_id'=>$data['school_id'],'code'=>$item['code']],['name'=>$item['name'],'applies_to'=>'STUDENT']);
                $rate = FeeRate::updateOrCreate(['school_id'=>$data['school_id'],'academic_session_id'=>$session->id,'fee_structure_id'=>$structure->id,'fee_category_id'=>$category->id],['amount'=>$item['amount'],'status'=>'ACTIVE']);
                $rates[] = [$category->id, $rate->amount];
            }
            foreach (Family::where('school_id', $data['school_id'])->get() as $family) {
                foreach ($rates as [$categoryId, $amount]) {
                    FamilyFeeCharge::firstOrCreate(
                        ['school_id'=>$data['school_id'],'family_id'=>$family->id,'academic_session_id'=>$session->id,'fee_category_id'=>$categoryId],
                        ['amount'=>$amount,'paid_amount'=>0,'balance_amount'=>$amount,'due_date'=>$session->starts_on,'status'=>'DUE']
                    );
                }
            }
        });

        return redirect()->route('fees.index')->with('status', 'Tetapan yuran sekolah berjaya disimpan untuk semua murid sekolah.');
    }
}
