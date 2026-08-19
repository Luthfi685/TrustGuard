<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProgress extends Model
{
    use HasFactory;

    protected $table = 'user_progress';

    protected $fillable = [
        'session_id',
        'points',
        'level',
        'completed_quizzes',
    ];

    protected $casts = [
        'completed_quizzes' => 'array',
    ];

    public static function getForSession(string $sessionId): self
    {
        return self::firstOrCreate(
            ['session_id' => $sessionId],
            ['points' => 0, 'level' => 1, 'completed_quizzes' => []]
        );
    }

    public function getLevelTitleAttribute(): string
    {
        return match ($this->level) {
            1 => 'Digital Beginner',
            2 => 'Cyber Explorer',
            3 => 'Security Sentinel',
            4 => 'Threat Hunter',
            5 => 'Trust Guardian',
            default => 'Trust Guardian',
        };
    }
}
