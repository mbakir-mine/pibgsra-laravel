<?php
namespace App\Models; use Illuminate\Database\Eloquent\Model;
class ReceiptSequence extends Model { public $timestamps=false; protected $fillable=['school_id','receipt_year','last_number']; protected $primaryKey=null; public $incrementing=false; }
