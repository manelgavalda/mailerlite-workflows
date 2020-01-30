<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubscriberJoinTrigger extends Model
{
    protected $fillable = ['group_id'];

    public function value()
    {
        return "When subscriber joins a group {$this->group->name}";
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
