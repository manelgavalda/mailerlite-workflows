<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Step extends Model
{
    protected $fillable = ['stepable_type', 'stepable_id', 'parentable_type', 'parentable_id'];

    public function value()
    {
        return $this->stepable->value();
    }

    public function stepable()
    {
        return $this->morphTo();
    }

    public function parentable()
    {
        return $this->morphTo();
    }
}
