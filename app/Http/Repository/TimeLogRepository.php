<?php

namespace App\Http\Repository;

use Carbon\Carbon;
use App\Models\TimeLog;

class TimeLogRepository
{
    public function getTimeLogHours()
    {
        // Fetch all time logs or apply filtering here as per your logic
        return TimeLog::with(['user', 'subproject.project.department'])->get();
    }
    public function filterTimeLogs($filters){
        $query = TimeLog::query();

        if ($filters['user_id']) {
            $query->where('user_id', $filters['user_id']);
        }

        if ($filters['department_id']) {
            $query->whereHas('subproject.project', function ($q) use ($filters) {
                $q->where('department_id', $filters['department_id']);
            });
        }

        if ($filters['project_id']) {
            $query->whereHas('subproject', function ($q) use ($filters){
                $q->where('project_id', $filters['project_id']);
            });
        }

        if ($filters['subproject_id']) {
            $query->where('subproject_id', $filters['subproject_id']);
        }

        if ($filters['start_date']) {
            $query->where('date', '>=', Carbon::parse($filters['start_date']));
        }

        if ($filters['end_date']) {
            $query->where('date', '<=', Carbon::parse($filters['end_date']));
        }
        $timeLogs= $query->with(['subproject.project', 'subproject.project.department', 'user'])->get();
        return $timeLogs;
    }
}
