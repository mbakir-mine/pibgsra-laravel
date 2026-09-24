<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class PaymentItemClaim extends Model { protected $fillable=['payment_id','student_fee_charge_id','active']; protected $casts=['active'=>'boolean']; public function studentFeeCharge(){return $this->belongsTo(StudentFeeCharge::class);} }
