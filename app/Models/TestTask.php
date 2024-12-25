<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TestTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'file',
        'time_limit_in_weeks', // Время на выполнение в неделях
    ];

    /**
     * Get the statuses associated with the test task.
     *
     * @return HasMany
     */
    public function statuses(): HasMany
    {
        return $this->hasMany(TestTaskStatus::class);
    }
}