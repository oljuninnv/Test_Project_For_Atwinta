<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    public $table = 'departments';

    public $fillable = ['name'];

    public function workers()
    {
        return $this->hasMany(Worker::class);
    }
}
