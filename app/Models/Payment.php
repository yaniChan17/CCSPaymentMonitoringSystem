<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    /** @use HasFactory<\Database\Factories\PaymentFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'student_id',
        'recorded_by',
        'fee_schedule_id',
        'amount',
        'payment_date',
        'status',
        'payment_method',
        'reference_number',
        'notes',
        'recorded_at',
        'is_late',
        'edited_by',
        'edited_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'payment_date' => 'date',
            'recorded_at' => 'datetime',
            'edited_at' => 'datetime',
            'is_late' => 'boolean',
        ];
    }

    /**
     * Get the student who made this payment.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the treasurer who recorded this payment.
     */
    public function treasurer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    /**
     * Get the user who recorded this payment (alias for treasurer).
     */
    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    /**
     * Get the fee schedule this payment is for.
     */
    public function feeSchedule(): BelongsTo
    {
        return $this->belongsTo(FeeSchedule::class);
    }

    /**
     * Get the user who edited this payment.
     */
    public function editor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'edited_by');
    }

    /**
     * Get audit logs for this payment.
     */
    public function auditLogs()
    {
        return $this->hasMany(PaymentAuditLog::class);
    }

    /**
     * Get the recorder of this payment (alias).
     */
    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
