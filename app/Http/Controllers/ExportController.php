<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\ExportServices;
use Illuminate\Support\Facades\Response;

class ExportController extends Controller
{
    public $exportServices;
    
    public function __construct(ExportServices $exportServices)
    {
        $this->exportServices = $exportServices;
        
    }
    public function exportToCsv()
    {
        $headers = [
            'Content-type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename=time_log_hours.csv',
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        // Get all time logs or we can filter as needed could be one query with all filters
        $response = $this->exportServices->timeLogHoursExport();
        return Response::stream($response, 200, $headers);
    }

}
