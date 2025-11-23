<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
     protected $fillable = [
        'name',
        'explanation',
        'status',
        'end_time',
        'end_date',
        'user_id',
        'project_id',
        'hours',
        'types',
        'client_name',
    ];

        public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

}