<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActionStep extends Model
{
    protected $fillable = ['actionable_type', 'actionable_id'];

    public function value()
    {
        return $this->actionable->value();
    }

    public function actionable()
    {
        return $this->morphTo();
    }
}
