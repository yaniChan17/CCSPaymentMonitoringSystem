<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'program',
        'year_level',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function students()
    {
        return $this->hasMany(User::class)->where('role', 'student');
    }

    public function treasurers()
    {
        return $this->hasMany(User::class)->where('role', 'treasurer');
    }

    public function feeSchedules()
    {
        return $this->hasMany(FeeSchedule::class, 'target_block_id');
    }

    public function announcements()
    {
        return $this->hasMany(Announcement::class, 'target_block_id');
    }
}
