<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    public function students()
    {
        return $this->hasMany(User::class, 'block_id')->where('role', 'student');
    }

    public function treasurer()
    {
        return $this->hasOne(User::class, 'block_id')->where('role', 'treasurer');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'block_id');
    }
}
