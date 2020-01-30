<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DelayStep extends Model
{
    protected $fillable = ['time_to_wait', 'time_unit'];

    public function value()
    {
        return "Wait {$this->time_to_wait} {$this->time_unit}(s)";
    }
}
