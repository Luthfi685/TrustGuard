<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scan extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'url',
        'domain',
        'scheme',
        'ip_address',
        'trust_score',
        'status',
        'ssl_info',
        'rdap_info',
        'threat_info',
        'header_info',
        'recommendations',
    ];

    protected $casts = [
        'ssl_info' => 'array',
        'rdap_info' => 'array',
        'threat_info' => 'array',
        'header_info' => 'array',
        'recommendations' => 'array',
    ];

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'safe' => '#22C55E',
            'warning' => '#F59E0B',
            'danger' => '#EF4444',
            default => '#6B7280',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'safe' => 'Terpercaya',
            'warning' => 'Perlu Waspada',
            'danger' => 'Berisiko Tinggi',
            default => 'Tidak Diketahui',
        };
    }
}
