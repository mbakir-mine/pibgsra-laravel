<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Student extends Model { protected $fillable=['school_id','family_id','full_name','student_identifier','year_level','is_active']; protected $casts=['is_active'=>'boolean']; public function school(){return $this->belongsTo(School::class);} public function family(){return $this->belongsTo(Family::class);} public function enrollments(){return $this->hasMany(StudentEnrollment::class);} public function charges(){return $this->hasMany(StudentFeeCharge::class);} }
