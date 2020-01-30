<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConditionStep extends Model
{
    protected $fillable = ['condition_type_id', 'conditionable_type', 'conditionable_id'];

    public function value()
    {
        return $this->conditionable->value();
    }

    public function conditionable()
    {
        return $this->morphTo();
    }
}
