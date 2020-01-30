<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Trigger extends Model
{
    protected $fillable = ['triggerable_type', 'triggerable_id'];

    public function value()
    {
        return $this->triggerable->value();
    }

    public function triggerable()
    {
        return $this->morphTo();
    }

    public function workflow()
    {
        return $this->hasOne(Workflow::class);
    }
}
