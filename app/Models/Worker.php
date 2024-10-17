<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Position;
use App\Models\User;
use App\Models\Department;

class Worker extends Model
{
    use HasFactory;

    public $table = 'workers';

    protected $fillable = [
        'user_id',
        'department_id',
        'position_id',
        'adopted_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }
}
