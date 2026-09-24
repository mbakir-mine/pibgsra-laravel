<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class FeeCategory extends Model { public $timestamps=false; protected $fillable=['school_id','code','name','applies_to']; public function school(){return $this->belongsTo(School::class);} }
