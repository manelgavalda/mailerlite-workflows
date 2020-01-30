<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Workflow extends Model
{
    protected $fillable = ['name', 'trigger_id'];

    public function trigger()
    {
        return $this->belongsTo(Trigger::class);
    }
}
