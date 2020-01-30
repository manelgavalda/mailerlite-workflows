<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Step extends Model
{
    protected $fillable = ['stepable_type', 'stepable_id', 'workflow_id'];

    public function value()
    {
        return $this->steapeable->value();
    }

    public function stepable()
    {
        return $this->morphTo();
    }
}
