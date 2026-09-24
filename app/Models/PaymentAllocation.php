<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class PaymentAllocation extends Model { protected $fillable=['school_id','payment_id','family_fee_charge_id','student_fee_charge_id','amount']; protected $casts=['amount'=>'decimal:2']; public function payment(){return $this->belongsTo(Payment::class);} }
