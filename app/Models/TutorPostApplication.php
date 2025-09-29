<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TutorPostApplication extends Model
{
    use HasFactory;

    protected $table = 'tutor_applications';
    protected $fillable = [
        'tutor_post_id',
        'user_id',
        'message',
        'status',
        'created_at',
        'updated_at',
    ];

    public function tutorPost()
    {
        return $this->belongsTo(TutorPost::class, 'tutor_post_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
