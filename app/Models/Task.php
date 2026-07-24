<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'is_completed',
        'due_date',
        'category',
        'sub_category',
        'priority',
    ];

    protected $casts = [
        'is_completed' => 'boolean',
    ];
}