<?php
namespace App\Http\Controllers;
use App\Models\Family;
class StatementController extends Controller { public function show(Family $family){$family->load(['school','students','charges','payments.receipt']); return view('statements.show',['family'=>$family,'totalCharges'=>$family->charges->sum('amount'),'totalPaid'=>$family->charges->sum('paid_amount'),'balance'=>$family->charges->sum('balance_amount')]);} }
