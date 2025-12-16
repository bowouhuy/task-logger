<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Daily Task</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="row g-4">
        <div class="col-lg-4">

            <div class="card shadow-sm border-0">
                <div class="card-body p-4">

                    <h4 class="mb-4 text-center fw-semibold">
                        Daily Task Input
                    </h4>

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0 ps-3">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('tasks.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Project</label>

                        <div class="input-group">
                            <select
                                name="project_id"
                                id="projectSelect"
                                class="form-select"
                                required
                            >
                                <option value="">Select project</option>
                                @foreach($projects as $project)
                                    <option value="{{ $project->id }}">
                                        {{ $project->name }}
                                    </option>
                                @endforeach
                            </select>

                            <button
                                type="button"
                                class="btn btn-outline-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#projectModal"
                            >
                                +
                            </button>
                        </div>
                    </div>


                    <!-- Modal for adding new project -->
                    <div
                        class="modal fade"
                        id="projectModal"
                        tabindex="-1"
                        aria-hidden="true"
                    >
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">

                                <div class="modal-header">
                                    <h5 class="modal-title">New Project</h5>
                                    <button
                                        type="button"
                                        class="btn-close"
                                        data-bs-dismiss="modal"
                                    ></button>
                                </div>

                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Project Name</label>
                                        <input
                                            type="text"
                                            id="newProjectName"
                                            class="form-control"
                                            placeholder="e.g. Website Revamp"
                                        >
                                    </div>

                                    <div
                                        class="alert alert-danger d-none"
                                        id="projectError"
                                    ></div>
                                </div>

                                <div class="modal-footer">
                                    <button
                                        type="button"
                                        class="btn btn-secondary"
                                        data-bs-dismiss="modal"
                                    >
                                        Cancel
                                    </button>

                                    <button
                                        type="button"
                                        class="btn btn-primary"
                                        onclick="createProject()"
                                    >
                                        Save Project
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- End Modal -->

                    <div class="mb-3">
                        <label class="form-label">Problem / Kegiatan</label>
                        <textarea
                            name="activity"
                            rows="4"
                            class="form-control"
                            placeholder="What are you working on?"
                            required
                        ></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">PIC</label>
                        <input
                            type="text"
                            name="pic"
                            class="form-control"
                            placeholder="Person in charge"
                        >
                    </div>

                    <div class="d-grid gap-2">
                    <button
                        type="submit"
                        name="action"
                        value="todo"
                        class="btn btn-outline-secondary"
                    >
                        Record as TODO
                    </button>

                    <button
                        type="submit"
                        name="action"
                        value="doing"
                        class="btn btn-primary"
                    >
                        Start DOING
                    </button>
                </div>
                </form>


                </div>
            </div>

            <p class="text-center text-muted small mt-3">
                Simple daily task logging system
            </p>

        </div>
        <div class="col-lg-8">

            <!-- Filter -->
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-body">
                    <form method="GET" class="row g-2 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label small">Project</label>
                            <select name="project_id" class="form-select">
                                <option value="">All Projects</option>
                                @foreach($projects as $project)
                                    <option
                                        value="{{ $project->id }}"
                                        @selected(request('project_id') == $project->id)
                                    >
                                        {{ $project->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label small">Status</label>
                            <select name="status" class="form-select">
                                <option value="">All</option>
                                @foreach(['todo','doing','waiting','done'] as $st)
                                    <option
                                        value="{{ $st }}"
                                        @selected(request('status') === $st)
                                    >
                                        {{ ucfirst($st) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <button class="btn btn-outline-primary w-100">
                                Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Task List -->
            <div class="card shadow-sm border-0">
                <div class="card-body p-0">

                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Project</th>
                                <th>Activity</th>
                                <th>Status</th>
                                <th class="text-end">Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tasks as $task)
                                <tr>
                                    <td class="fw-semibold">
                                        {{ $task->project->name }}
                                    </td>

                                    <td>
                                        {{ Str::limit($task->activity, 60) }}
                                    </td>

                                    <td>
    <div class="d-flex flex-wrap gap-1">

        @foreach(['todo','doing','waiting','done'] as $st)
            <form
                method="POST"
                action="{{ route('tasks.update-status', $task) }}"
            >
                @csrf
                <input type="hidden" name="status" value="{{ $st }}">

                <button
                    class="btn btn-sm
                        {{ $task->status === $st
                            ? 'btn-primary'
                            : 'btn-outline-secondary'
                        }}"
                    @disabled($task->status === $st)
                >
                    {{ strtoupper($st) }}
                </button>
            </form>
        @endforeach

    </div>
</td>



                                    <td class="text-end text-muted small">
                                        {{ $task->created_at->format('H:i') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        No tasks found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>

                @if($tasks->hasPages())
                    <div class="card-footer bg-white">
                        {{ $tasks->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
function createProject() {
    const nameInput = document.getElementById('newProjectName');
    const errorBox = document.getElementById('projectError');
    const name = nameInput.value.trim();

    errorBox.classList.add('d-none');
    errorBox.innerText = '';

    if (!name) {
        errorBox.innerText = 'Project name is required.';
        errorBox.classList.remove('d-none');
        return;
    }

    fetch("{{ route('projects.store') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ name })
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => { throw err; });
        }
        return response.json();
    })
    .then(project => {
        const select = document.getElementById('projectSelect');
        const option = document.createElement('option');

        option.value = project.id;
        option.text = project.name;
        option.selected = true;

        select.appendChild(option);

        nameInput.value = '';

        const modal = bootstrap.Modal.getInstance(
            document.getElementById('projectModal')
        );
        modal.hide();
    })
    .catch(err => {
        errorBox.innerText = err.message || 'Failed to create project.';
        errorBox.classList.remove('d-none');
    });
}
</script>


</body>
</html>
