<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    public const OWNER = 'OWNER';
    public const STATE_ADMIN = 'STATE_ADMIN';
    public const DISTRICT_ADMIN = 'DISTRICT_ADMIN';
    public const SCHOOL_ADMIN = 'SCHOOL_ADMIN';
    public const PARENT = 'PARENT';

    public const ADMIN_ROLES = [
        self::OWNER,
        self::STATE_ADMIN,
        self::DISTRICT_ADMIN,
        self::SCHOOL_ADMIN,
    ];

    public $timestamps = false;

    protected $fillable = [
        'school_id',
        'user_id',
        'role',
        'state',
        'district',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
