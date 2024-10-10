<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubProject extends Model
{
    use HasFactory;
    protected $table = 'subprojects';

    public function project() {
        return $this->belongsTo(Project::class);
    }
    
    public function timeLogs() {
        return $this->hasMany(TimeLog::class);
    }
    
}
