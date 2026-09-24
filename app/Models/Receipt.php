<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Receipt extends Model { protected $fillable=['school_id','payment_id','receipt_number','issued_at']; protected $casts=['issued_at'=>'datetime']; public function payment(){return $this->belongsTo(Payment::class);} }
