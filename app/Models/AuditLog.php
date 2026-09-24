<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class AuditLog extends Model { protected $fillable=['school_id','actor_user_id','action','entity_type','entity_id','old_values','new_values','reason','ip_address','user_agent']; protected $casts=['old_values'=>'array','new_values'=>'array']; public function actor(){return $this->belongsTo(User::class,'actor_user_id');} }
