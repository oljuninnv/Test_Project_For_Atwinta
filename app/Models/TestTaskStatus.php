<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    /**
     * Get the user associated with the test task status.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the test task associated with the test task status.
     *
     * @return BelongsTo
     */
    public function testTask(): BelongsTo
    {
        return $this->belongsTo(TestTask::class);
    }
}