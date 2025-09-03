<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiRecommendation extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_id',
        'tutor_id',
        'reason',
        'matching_score',
        'matching_factors',
        'was_helpful'
    ];

    protected $casts = [
        'matching_score' => 'decimal:2',
        'matching_factors' => 'array',
        'was_helpful' => 'boolean'
    ];

    public function conversation()
    {
        return $this->belongsTo(AiConversation::class, 'conversation_id');
    }

    public function tutor()
    {
        return $this->belongsTo(Tutor::class);
    }
} 