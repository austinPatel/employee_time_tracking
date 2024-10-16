<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubProject extends Model
{
    use HasFactory;
    protected $table = 'subprojects';

        /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'project_id',
        'name',
    ];

    public function project() {
        return $this->belongsTo(Project::class);
    }
    
    public function timeLogs() {
        return $this->hasMany(TimeLog::class);
    }
    
}
