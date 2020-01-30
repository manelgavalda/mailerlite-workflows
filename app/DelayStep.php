<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DelayStep extends Model
{
    protected $fillable = ['days_to_wait'];

    public function value()
    {
        return "Wait {$this->days_to_wait} day(s)";
    }
}
