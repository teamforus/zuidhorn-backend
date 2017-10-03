<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfficeSchedule extends Model
{
    protected $fillable = ['office_id', 'week_day', 'start_time', 'end_time', 
    'break_start', 'break_end'];

    protected $hidden = ['break_start', 'break_end'];
    
    public $timestamps = FALSE;

    public function getStartTimeAttribute($value)
    {
        $value = collect(explode(':', $value));
        $value->pop();
        
        return $value->map(function($val) {
            return intval($val) < 10 ? '0' . intval($val) : intval($val);
        })->implode(':');
    }

    public function getEndTimeAttribute($value)
    {
        $value = collect(explode(':', $value));
        $value->pop();
        
        return $value->map(function($val) {
            return intval($val) < 10 ? '0' . intval($val) : intval($val);
        })->implode(':');
    }
}
