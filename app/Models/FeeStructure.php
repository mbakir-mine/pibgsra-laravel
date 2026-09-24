<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class FeeStructure extends Model { protected $fillable=['school_id','academic_session_id','name','status','approved_annual_limit']; protected $casts=['approved_annual_limit'=>'decimal:2']; public function rates(){return $this->hasMany(FeeRate::class);} }
