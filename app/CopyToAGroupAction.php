<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CopyToAGroupAction extends Model
{
    protected $fillable = ['group_id'];

    public function value()
    {
        return "Copy to a group {$this->group->name}";
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
