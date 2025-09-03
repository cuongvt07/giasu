<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'tutor_id',
        'booking_id',
        'rating',
        'comment',
        'is_anonymous'
    ];

    protected $casts = [
        'rating' => 'decimal:1',
        'is_anonymous' => 'boolean'
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function tutor()
    {
        return $this->belongsTo(Tutor::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
} 