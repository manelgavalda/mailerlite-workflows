<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TriggerField extends Model
{
    protected $fillable = ['trigger_id', 'name', 'type', 'value'];
}
