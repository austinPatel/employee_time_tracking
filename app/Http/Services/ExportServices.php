<?php
namespace App\Http\Services;

use App\Http\Repository\TimeLogRepository;

class ExportServices
{
    public $timeLogRepository;

    public function __construct(TimeLogRepository $timeLogRepository)
    {
        $this->timeLogRepository = $timeLogRepository;
        
    }
    public function timeLogHoursExport(){
        // Retrieve the time logs from the repository
        $timeLogs = $this->timeLogRepository->getTimeLogHours();

        return  function () use ($timeLogs) {
            // Open output buffer as file
            $file = fopen('php://output', 'w');

            // Add the CSV column headers
            fputcsv($file, ['Employee', 'Department', 'Project', 'Subproject', 'Date', 'Start Time', 'End Time', 'Total Hours']);

            // Write each time log row to the CSV
            foreach ($timeLogs as $timelog) {
                fputcsv($file, [
                    $timelog->user->name,
                    $timelog->subproject->project->department->name,
                    $timelog->subproject->project->name,
                    $timelog->subproject->name,
                    $timelog->date,
                    $timelog->start_time,
                    $timelog->end_time,
                    $timelog->total_hours
                ]);
            }

            // Close the file
            fclose($file);
        };

    }
    
}
