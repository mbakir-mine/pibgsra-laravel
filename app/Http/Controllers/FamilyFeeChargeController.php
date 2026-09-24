<?php
namespace App\Http\Controllers;
use App\Models\Family;
use App\Models\FamilyFeeCharge;
use App\Models\FeeCategory;
use App\Models\AcademicSession;
use Illuminate\Http\Request;
class FamilyFeeChargeController extends Controller { public function index(){return view('charges.index',['charges'=>FamilyFeeCharge::with(['family','family.school'])->latest()->paginate(15)]);} public function create(){return view('charges.create',['families'=>Family::orderBy('name')->get(),'categories'=>FeeCategory::where('applies_to','FAMILY')->orderBy('name')->get(),'sessions'=>AcademicSession::orderByDesc('starts_on')->get()]);} public function store(Request $request){$data=$request->validate(['school_id'=>'required|exists:schools,id','family_id'=>'required|exists:families,id','academic_session_id'=>'required|exists:academic_sessions,id','fee_category_id'=>'required|exists:fee_categories,id','amount'=>'required|numeric|min:0','due_date'=>'required|date']); FamilyFeeCharge::create($data+['paid_amount'=>0,'balance_amount'=>$data['amount'],'status'=>'DUE']); return redirect()->route('charges.index')->with('status','Caj keluarga berjaya direkodkan.');} }
