<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    
    public function department() {
        return $this->belongsTo(Department::class);
    }
    
    public function subprojects() {
        return $this->hasMany(Subproject::class);
    }
    
}
