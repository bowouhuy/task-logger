<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'task_date',
        'project_id',
        'start_time',
        'end_time',
        'activity',
        'pic',
        'status'
    ];

    public function details()
    {
        return $this->hasMany(TaskDetail::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
