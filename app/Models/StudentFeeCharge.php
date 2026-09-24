<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class StudentFeeCharge extends Model { protected $fillable=['school_id','student_id','academic_session_id','fee_category_id','fee_rate_id','billing_period_type','billing_year','billing_month','original_amount','adjustment_amount','final_amount','paid_amount','balance_amount','due_date','status']; protected $casts=['original_amount'=>'decimal:2','adjustment_amount'=>'decimal:2','final_amount'=>'decimal:2','paid_amount'=>'decimal:2','balance_amount'=>'decimal:2','due_date'=>'date']; public function student(){return $this->belongsTo(Student::class);} }
