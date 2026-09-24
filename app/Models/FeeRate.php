<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class FeeRate extends Model { public $timestamps=false; protected $fillable=['school_id','academic_session_id','fee_structure_id','fee_category_id','amount','status']; protected $casts=['amount'=>'decimal:2']; }
