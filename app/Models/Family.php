<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Family extends Model { protected $fillable=['school_id','family_code','name']; public function school(){return $this->belongsTo(School::class);} public function students(){return $this->hasMany(Student::class);} public function guardians(){return $this->belongsToMany(Guardian::class,'family_guardians')->withPivot('school_id','relationship');} public function charges(){return $this->hasMany(FamilyFeeCharge::class);} public function payments(){return $this->hasMany(Payment::class);} }
