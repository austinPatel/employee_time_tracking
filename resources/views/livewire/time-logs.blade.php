<div class="max-w-7xl mx-auto p-6 bg-white rounded-lg shadow-md">
    <table class="min-w-full border border-gray-300">
        <thead>
            <tr>
                <th class="border px-4 py-2">Department</th>
                <th class="border px-4 py-2">Project</th>
                <th class="border px-4 py-2">Subproject</th>
                <th class="border px-4 py-2">Date</th>
                <th class="border px-4 py-2">Start Time</th>
                <th class="border px-4 py-2">End Time</th>
                <th class="border px-4 py-2">Total Hours</th>
                <th class="border px-4 py-2">Actions</th>

            </tr>
        </thead>
        
        <tbody>
            @foreach ($timeLogs as $timelog)
                <tr>
                    <td class="border px-4 py-2">{{ $timelog->user->name }}</td>
                    <td class="border px-4 py-2">{{ $timelog->subproject->project->department->name }}</td>
                    <td class="border px-4 py-2">{{ $timelog->subproject->project->name }}</td>
                    <td class="border px-4 py-2">{{ $timelog->subproject->name }}</td>
                    <td class="border px-4 py-2">{{ $timelog->date }}</td>
                    <td class="border px-4 py-2">{{ $timelog->start_time }}</td>
                    <td class="border px-4 py-2">{{ $timelog->end_time }}</td>
                    <td class="border px-4 py-2">{{ $timelog->total_hours }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
