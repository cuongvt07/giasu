<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassLevel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'education_level',
        'description',
        'order',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer'
    ];

    public function tutors()
    {
        return $this->belongsToMany(Tutor::class, 'tutor_class_levels')
            ->withPivot('price_per_hour', 'experience_details')
            ->withTimestamps();
    }

    public function studentProfiles()
    {
        return $this->hasMany(StudentProfile::class);
    }
}
