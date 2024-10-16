<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeLog extends Model
{
    
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id', 
        'subproject_id',
        'date',
        'start_time',
        'end_time',
        'total_hours'
    ];
    
    public static function boot()
    {
        parent::boot();

        static::creating(function ($timeLog) {
            $timeLog->calculateTotalHours();
        });

        static::updating(function ($timeLog) {
            $timeLog->calculateTotalHours();
        });
    }


    public function user() {
        return $this->belongsTo(User::class);
    }
    
    public function subproject() {
        return $this->belongsTo(Subproject::class);
    }
    public function calculateTotalHours()
    {
        $start = strtotime($this->start_time);
        $end = strtotime($this->end_time);

        // Calculate total hours as difference between start and end time in hours
        $this->total_hours = ($end - $start) / 3600;  // Divide by 3600 to get hours
    }

    
}
