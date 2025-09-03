<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AIConversation extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ai_conversations';

    protected $fillable = [
        'user_id',
        'status'
    ];

    protected $casts = [
        'context_data' => 'array',
        'last_interaction' => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(AIMessage::class, 'conversation_id');
    }

    public function recommendations()
    {
        return $this->hasMany(AiRecommendation::class, 'conversation_id');
    }
}
