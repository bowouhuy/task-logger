<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;

class ProjectController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:projects,name'],
        ]);

        $project = Project::create([
            'name' => $data['name'],
        ]);

        return response()->json([
            'id'   => $project->id,
            'name' => $project->name,
        ]);
    }
}
