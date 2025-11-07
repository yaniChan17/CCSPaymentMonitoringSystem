<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'message',
        'target_role',
        'target_block_id',
        'posted_by'
    ];

    public function poster()
    {
        return $this->belongsTo(User::class, 'posted_by');
    }

    public function targetBlock()
    {
        return $this->belongsTo(Block::class, 'target_block_id');
    }
}
