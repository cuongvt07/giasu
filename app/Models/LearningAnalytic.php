<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LearningAnalytic extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'subject_id',
        'performance_score',
        'strengths',
        'weaknesses',
        'ai_recommendations',
        'progress_history'
    ];

    protected $casts = [
        'performance_score' => 'decimal:2',
        'strengths' => 'array',
        'weaknesses' => 'array',
        'ai_recommendations' => 'array',
        'progress_history' => 'array'
    ];

    public function student()
    {
        return $this->belongsTo(StudentProfile::class, 'student_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
} 