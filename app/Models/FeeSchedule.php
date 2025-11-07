<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeeSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'academic_year',
        'semester',
        'amount',
        'due_date',
        'late_penalty',
        'allow_partial',
        'target_program',
        'target_year',
        'target_block_id',
        'instructions',
        'status',
        'created_by',
        'locked_at'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'late_penalty' => 'decimal:2',
        'allow_partial' => 'boolean',
        'due_date' => 'date',
        'locked_at' => 'datetime'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function targetBlock()
    {
        return $this->belongsTo(Block::class, 'target_block_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function isOverdue()
    {
        return now()->gt($this->due_date);
    }

    public function daysUntilDue()
    {
        return now()->diffInDays($this->due_date, false);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
