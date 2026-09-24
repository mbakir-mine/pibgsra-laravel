<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    protected $fillable = [
        'name',
        'code',
        'district',
        'category',
    ];

    public function families()
    {
        return $this->hasMany(Family::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function sessions()
    {
        return $this->hasMany(AcademicSession::class);
    }
}
