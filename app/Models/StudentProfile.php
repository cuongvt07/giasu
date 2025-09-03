<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentProfile extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'class_level_id',
        'learning_style',
        'learning_goals',
        'subjects_need_help',
        'preferred_schedule',
        'learning_history',
        'special_requirements'
    ];

    protected $casts = [
        'subjects_need_help' => 'array',
        'preferred_schedule' => 'array',
        'learning_history' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function classLevel()
    {
        return $this->belongsTo(ClassLevel::class);
    }

    public function learningAnalytics()
    {
        return $this->hasMany(LearningAnalytic::class, 'student_id');
    }
}
