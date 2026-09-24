<?php
namespace App\Http\Controllers;
use App\Models\MonthClosing; use App\Models\School; use Illuminate\Http\Request;
class MonthClosingController extends Controller { public function index(){return view('month-closing.index',['closings'=>MonthClosing::with('school')->latest()->paginate(15),'schools'=>School::orderBy('name')->get()]);} public function store(Request $request){$data=$request->validate(['school_id'=>'required|exists:schools,id','year'=>'required|integer|min:2020|max:2100','month'=>'required|integer|min:1|max:12']); MonthClosing::updateOrCreate(['school_id'=>$data['school_id'],'year'=>$data['year'],'month'=>$data['month']],['closed_by'=>$request->user()->id,'closed_at'=>now(),'status'=>'CLOSED']); return back()->with('status','Bulan berjaya ditutup.');} }
