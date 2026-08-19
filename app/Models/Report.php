<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'url',
        'domain',
        'category',
        'description',
        'status',
        'submitter_ip',
    ];

    public function getCategoryBadgeAttribute(): string
    {
        return match ($this->category) {
            'Phishing' => 'bg-rose-50 text-rose-700 border-rose-200',
            'Penipuan' => 'bg-amber-50 text-amber-700 border-amber-200',
            'Website Palsu' => 'bg-purple-50 text-purple-700 border-purple-200',
            'Pencurian Data' => 'bg-red-50 text-red-700 border-red-200',
            default => 'bg-brand-50 text-brand-700 border-brand-200',
        };
    }
}
