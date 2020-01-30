<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailStep extends Model
{
    protected $fillable = ['subject', 'content'];

    public function value()
    {
        return $this->subject;
    }
}
