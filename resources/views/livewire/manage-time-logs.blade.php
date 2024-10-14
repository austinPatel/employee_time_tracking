<div class="max-w-7xl mx-auto p-6 bg-white rounded-lg shadow-md">
    @if (session()->has('message'))
        <div id ="successMessage" class="alert alert-success bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('message') }}</span>
        </div>
    @endif

    <!-- filter logs by employee, department, project, subproject, and date range -->
    <div class="mb-4">
        <label for="employee" class="block">Employee</label>
        <select wire:model="filters.employee_id" id="employee" class="border rounded">
            <option value="">All Employees</option>
            @foreach ($employees as $id => $name)
                <option value="{{ $id }}">{{ $name }}</option>
            @endforeach
        </select>

        <label for="department" class="block">Department</label>
        <select wire:model="filters.department_id" id="department" class="border rounded">
            <option value="">All Departments</option>
            @foreach ($departments as $id => $name)
                <option value="{{ $id }}">{{ $name }}</option>
            @endforeach
        </select>       

        <label for="project" class="block">Project</label>
        <select wire:model="filters.project_id" id="project" class="border rounded">
            <option value="">All Projects</option>
            @foreach ($projects as $id => $name)
                <option value="{{ $id }}">{{ $name }}</option>
            @endforeach
        </select>

        <label for="subproject" class="block">Subproject</label>
        <select wire:model="filters.subproject_id" id="subproject" class="border rounded">
            <option value="">All Subprojects</option>
            @foreach ($subprojects as $id => $name)
                <option value="{{ $id }}">{{ $name }}</option>
            @endforeach
        </select>


        <label for="start-date" class="block">Start Date</label>
        <input type="date" wire:model="filters.start_date" id="start-date" class="border rounded ">

        <label for="end-date" class="block">End Date</label>
        <input type="date" wire:model="filters.end_date" id="end-date" class="border rounded ">

        <button wire:click="filterLogs" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded">Filter Logs</button>
        <button wire:click="exportLogs" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded">Export CSV</button>

        <!-- <button class="mt-4 bg-blue-500 text-white px-4 py-2 rounded">
            <a href="{{ route('export.time.logs')}}" :active="request()->routeIs('export.time.logs')">Export TimeLog </a>   
        </button> -->

    </div>
    @if (session()->has('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif
    <!-- Time Logs Table -->
    @if($timeLogs)
    <h2 class="text-2xl font-bold mb-6">
            Timelog Hours List
    </h2>

    <table class="min-w-full border border-gray-300">
        <thead>
            <tr>
                <th class="border px-4 py-2">Employee</th>
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
                    <td class="border px-4 py-2">
                        <!-- <button wire:click="editTimeLog({{ $timelog->id }})" class="bg-yellow-500 text-white px-2 py-1 rounded">Edit</button> -->
                        <button class="mt-4 bg-blue-500 text-white px-4 py-2 rounded">
                        <a href="{{ route('edit.log.hours',$timelog->id) }}" :active="request()->routeIs('edit.log.hours')">
                            {{ __('Edit') }}
                        </a>
                        </button>
                        
                        <button wire:confirm="Are you sure you want to delete this log hours?" wire:click="deleteTimeLog({{ $timelog->id }})" class="bg-red-500 text-white px-2 py-1 rounded">Delete</button>

                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @else
        <p class="mt-6 text-gray-500 leading-relaxed text-center">No logs found.</p>
    @endif

    
</div>

