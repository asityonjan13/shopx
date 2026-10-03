<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kyc extends Model
{
    protected $fillable = [
        'user_id',
        'status',
        'rejected_reason',
        'verified_at',
        'full_name',
        'date_of_birth',
        'gender',
        'full_address',
        'document_type',
        'document_scan_copy',
    ];

    protected function casts(): array
    {
        return [
            'verified_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
