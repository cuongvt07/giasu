<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class TutorPost extends Model
{
    use HasFactory;

    protected $table = 'tutor_posts';
    protected $fillable = [
        'user_id', 'subject_id', 'class_level_id', 'goal', 'description', 'budget_min', 'budget_max', 'budget_unit', 'sessions_per_week', 'session_length_min', 'schedule_notes', 'mode', 'address_line', 'student_count', 'student_age', 'special_notes', 'qualifications', 'min_experience_yr', 'contact_phone', 'contact_email', 'deadline_at', 'status', 'published_at', 'created_at', 'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function classLevel()
    {
        return $this->belongsTo(ClassLevel::class);
    }

    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }

        // Lượt ứng tuyển vào tin đăng
    public function applications()
    {
        return $this->hasMany(\App\Models\TutorPostApplication::class, 'tutor_post_id');
    }
}
