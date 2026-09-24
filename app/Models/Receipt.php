<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Receipt extends Model { protected $fillable=['school_id','payment_id','receipt_number','issued_at','status','cancelled_at','cancelled_by','cancellation_reason']; protected $casts=['issued_at'=>'datetime','cancelled_at'=>'datetime']; public function payment(){return $this->belongsTo(Payment::class);} public function cancelledBy(){return $this->belongsTo(User::class,'cancelled_by');} }
