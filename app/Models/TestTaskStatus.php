<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestTaskStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'test_task_id',
        'status',
        'start_date',
        'end_date',
    ];

    // Определение связи с User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Определение связи с TestTask
    public function testTask()
    {
        return $this->belongsTo(TestTask::class);
    }
}
