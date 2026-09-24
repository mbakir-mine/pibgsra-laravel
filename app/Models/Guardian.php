<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Guardian extends Model { protected $fillable=['school_id','user_id','full_name','ic_number','phone']; public function families(){return $this->belongsToMany(Family::class,'family_guardians')->withPivot('school_id','relationship');} public function user(){return $this->belongsTo(User::class);} }
