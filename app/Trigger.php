<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Trigger extends Model
{
    protected $fillable = ['icon', 'name', 'description', 'workflow_id', 'subscriber_group_id'];

    public function field()
    {
        return $this->hasOne(TriggerField::class);
    }

    public function value()
    {
        return "{$this->name} {$this->field->value}";
    }
}
