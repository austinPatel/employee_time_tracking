<?php

namespace App\Http\Repository;

use Carbon\Carbon;
use App\Models\TimeLog;
// use Filament\Forms\Components\Builder;
// use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Builder; // Use the correct Builder


class TimeLogRepository
{
    public function getTimeLogHours()
    {
        // Fetch all time logs or apply filtering here as per your logic
        return TimeLog::with(['user', 'subproject.project.department'])->get();
    }

    
    public function filterTimeLogs(array $filters): Builder
    {
        $query = TimeLog::query();

        // if ($filters['user_id']) {
        //     $query->where('user_id', $filters['user_id']);
        // }

        // if ($filters['department_id']) {
        //     $query->whereHas('subproject.project', function ($q) use ($filters) {
        //         $q->where('department_id', $filters['department_id']);
        //     });
        // }

        // if ($filters['project_id']) {
        //     $query->whereHas('subproject', function ($q) use ($filters){
        //         $q->where('project_id', $filters['project_id']);
        //     });
        // }

        // if ($filters['subproject_id']) {
        //     $query->where('subproject_id', $filters['subproject_id']);
        // }

        // if ($filters['start_date']) {
        //     $query->where('date', '>=', Carbon::parse($filters['start_date']));
        // }

        // if ($filters['end_date']) {
        //     $query->where('date', '<=', Carbon::parse($filters['end_date']));
        // }
        // $timeLogs= $query->with(['subproject.project', 'subproject.project.department', 'user'])->get();
        if (!empty($filters['query'])) {
            $keyword = $filters['query'];
    
            // Group the keyword to all filters 
            $query->where(function ($q) use ($keyword) {
                $q->whereHas('user', function ($q) use ($keyword) {
                    $q->where('name', 'like', '%' . $keyword . '%');
                })
                ->orWhereHas('subproject.project', function ($q) use ($keyword) {
                    $q->where('name', 'like', '%' . $keyword . '%')
                      ->orWhereHas('department', function ($q) use ($keyword) {
                          $q->where('name', 'like', '%' . $keyword . '%');
                      });
                })
                ->orWhereHas('subproject', function ($q) use ($keyword) {
                    $q->where('name', 'like', '%' . $keyword . '%');
                });
            });
        }        
        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $startDate = $filters['start_date'];
            $endDate = $filters['end_date'];
    
            // Apply the date range filter after the grouped keyword search
            $query->whereBetween('date', [$startDate, $endDate]);
        }
    
        return $query;
    }
    
}
