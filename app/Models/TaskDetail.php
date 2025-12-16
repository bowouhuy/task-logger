<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskDetail extends Model
{
    protected $fillable = [
        'task_id',
        'status',
        'start_time',
        'end_time'
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
