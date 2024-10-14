<?php

use App\Models\User;
use App\Models\Project;
use App\Models\Department;
use App\Models\SubProject;
use App\Models\TimeLog;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('Employee create log hours', function () {


    // Create User with role 'employee'
    $employee = User::factory()->create(['role' => 'employee']);
    // create department
    $department = Department::factory()->create();
    // create project
    $project = Project::factory()->create(['department_id'=>$department->id]);
    //create subproject
    $subProject = SubProject::factory()->create(['project_id'=>$project->id]);
    // create time log hours

    // Check login employee
    $this->actingAs($employee);

    $responseEmployee = TimeLog::factory()->create([
        'user_id'=>$employee->id,
        'subproject_id'=>$subProject->id,
        'date'=>'2024-10-11',
        'start_time'=>'10:00:00',
        'end_time'=>'13:00:00',
        'total_hours' => 3,

    ]);
    $this->assertDatabaseHas('time_logs', [
        'user_id' => $employee->id,
        'subproject_id' => $subProject->id,
    ]);


});

