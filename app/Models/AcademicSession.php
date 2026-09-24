<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class AcademicSession extends Model { public $timestamps=false; protected $fillable=['school_id','name','starts_on','ends_on','is_active']; protected $casts=['is_active'=>'boolean']; public function school(){return $this->belongsTo(School::class);} }
