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
        'tutor_id',
        'note',
        'status',
        'created_at',
        'updated_at',
    ];

    public function tutorPost()
    {
        return $this->belongsTo(TutorPost::class, 'tutor_post_id');
    }

    public function tutor()
    {
        return $this->belongsTo(\App\Models\Tutor::class, 'tutor_id');
    }
}