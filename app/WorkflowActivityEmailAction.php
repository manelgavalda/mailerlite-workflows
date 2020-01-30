<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WorkflowActivityEmailAction extends Model
{
    protected $fillable = ['name'];

    public function value()
    {
        return $this->name;
    }
}
