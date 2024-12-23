<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'file',
        'time_limit_in_weeks', // Время на выполнение в неделях
    ];

    // Определение связи с TestTaskStatus
    public function statuses()
    {
        return $this->hasMany(TestTaskStatus::class);
    }
}
