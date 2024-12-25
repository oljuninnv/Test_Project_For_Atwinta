<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    use HasFactory;

    public $table = 'departments';

    protected $fillable = ['name'];

    /**
     * Get the workers associated with the department.
     *
     * @return HasMany
     */
    public function workers(): HasMany
    {
        return $this->hasMany(Worker::class);
    }
}