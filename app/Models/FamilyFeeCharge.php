<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class FamilyFeeCharge extends Model { protected $fillable=['school_id','family_id','academic_session_id','fee_category_id','amount','paid_amount','balance_amount','due_date','status']; protected $casts=['amount'=>'decimal:2','paid_amount'=>'decimal:2','balance_amount'=>'decimal:2','due_date'=>'date']; public function family(){return $this->belongsTo(Family::class);} public function allocations(){return $this->hasMany(PaymentAllocation::class);} }
