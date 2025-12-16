<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\TaskDetail;

class TaskController extends Controller
{
    public function create(Request $request)
    {
        $query = Task::with('project')
            ->orderByDesc('created_at');

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        return view('tasks.create', [
            'projects' => Project::orderBy('name')->get(),
            'tasks'    => $query->paginate(10),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => ['required', 'exists:projects,id'],
            'activity'   => ['required', 'string'],
            'pic'        => ['nullable', 'string', 'max:255'],
            'action'     => ['required', 'in:todo,doing'],
        ]);

        $now = Carbon::now();
        $status = $validated['action'];

        // Create main task
        $task = Task::create([
            'task_date'  => $now->toDateString(),
            'project_id' => $validated['project_id'],
            'start_time' => $status === 'doing' ? $now : null,
            'end_time'   => null,
            'activity'   => $validated['activity'],
            'pic'        => $validated['pic'] ?? null,
            'status'     => $status,
        ]);

        // Create initial task detail (status history)
        TaskDetail::create([
            'task_id'    => $task->id,
            'status'     => $status,
            'start_time' => $status === 'doing' ? $now : null,
            'end_time'   => null,
        ]);

        return redirect()
            ->route('tasks.create')
            ->with(
                'success',
                $status === 'doing'
                    ? 'Task started at ' . $now->format('H:i')
                    : 'Task recorded as TODO'
            );
    }

    public function updateStatus(Request $request, Task $task)
    {
        $data = $request->validate([
            'status' => ['required', 'in:todo,doing,waiting,done'],
        ]);

        $newStatus = $data['status'];
        $now = Carbon::now();

        // Do nothing if same status
        if ($task->status === $newStatus) {
            return back();
        }

        // Close previous status detail (if exists and still open)
        TaskDetail::where('task_id', $task->id)
            ->whereNull('end_time')
            ->latest()
            ->first()
            ?->update(['end_time' => $now]);

        // Create new status detail
        TaskDetail::create([
            'task_id'    => $task->id,
            'status'     => $newStatus,
            'start_time' => $now,
            'end_time'   => $newStatus === 'done' ? $now : null,
        ]);

        // Update main task
        $task->update([
            'status'   => $newStatus,
            'end_time' => $newStatus === 'done' ? $now : null,
        ]);

        return back()->with('success', 'Task status updated to ' . strtoupper($newStatus));
    }
}
