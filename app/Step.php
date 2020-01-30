<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Step extends Model
{
    protected $fillable = ['workflow_id', 'stepable_type', 'stepable_id'];

    public function value()
    {
        return $this->stepable->value();
    }

    public function stepable()
    {
        return $this->morphTo();
    }
}
