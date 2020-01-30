<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WorkflowActivityCondition extends Model
{
    protected $fillable = ['workflow_activity_email_id', 'workflow_activity_email_action_id'];

    public function value()
    {
        return "{$this->workflowActivityEmail->value()} {$this->workflowActivityEmailAction->value()}";
    }

    public function workflowActivityEmail()
    {
        return $this->belongsTo(EmailStep::class);
    }

    public function workflowActivityEmailAction()
    {
        return $this->belongsTo(WorkflowActivityEmailAction::class);
    }
}
