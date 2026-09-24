<?php
namespace App\Models; use Illuminate\Database\Eloquent\Model;
class MonthClosing extends Model { protected $fillable=['school_id','year','month','closed_by','closed_at','status']; protected $casts=['closed_at'=>'datetime']; public function school(){return $this->belongsTo(School::class);} }
