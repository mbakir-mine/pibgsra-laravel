<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class StudentEnrollment extends Model { public $timestamps=false; protected $fillable=['school_id','student_id','academic_session_id','class_name','year_level','is_active']; protected $casts=['is_active'=>'boolean']; public function student(){return $this->belongsTo(Student::class);} public function session(){return $this->belongsTo(AcademicSession::class,'academic_session_id');} }
