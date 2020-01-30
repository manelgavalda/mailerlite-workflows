<?php

namespace Tests\Feature;

use App\Step;
use App\Action;
use App\Trigger;
use App\Workflow;
use App\StepType;
use App\DelayStep;
use App\EmailStep;
use App\Condition;
use Tests\TestCase;
use App\TriggerField;
use App\ConditionStep;
use App\SubscriberGroup;
use App\ConditionTrigger;
use App\WorkflowActivityCondition;
use App\WorkflowActivityEmailAction;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $workflow = $this->createWorkflow($this->createTrigger());

        $this->assertEquals(
            $workflow->trigger->value(),
            'When subscriber joins a group MailerLite'
        );

        $firstStep = $this->createFirstStep($workflow);

        $this->assertEquals(
            $firstStep->stepable->value(),
            'Wait 1 day(s)'
        );

        $secondStep = $this->createSecondStep($workflow);

        $this->assertEquals(
            $secondStep->stepable->value(),
            'Welcome to MailerLite'
        );

        $thirdStep = $this->createThirdStep($workflow, $secondStep);

        $this->assertEquals(
            $thirdStep->stepable->value(),
            'Welcome to MailerLite was opened'
        );
    }

    protected function createTrigger()
    {
        $subscriberJoinTrigger = Trigger::create([
            'icon' => 'icon',
            'name' => 'When subscriber joins a group',
            'description' => 'Workflow triggered when a subscriber joins your subscriber group.'
        ]);

        $subscriberJoinTrigger->field()->create([
            'name' => 'SubscriberGroup',
            'type' => 'select'
        ]);

        return $subscriberJoinTrigger;
    }

    protected function createWorkflow($subscriberJoinTrigger)
    {
        $workflow = Workflow::create([
            'name' => 'Welcome',
            'trigger_id' => $subscriberJoinTrigger->id
        ]);

        $workflow->trigger->field->update([
            'value' => 'MailerLite'
        ]);

        return $workflow;
    }

    protected function createFirstStep($workflow)
    {
        $firstStep = Step::create([
            'workflow_id' => $workflow->id,
            'stepable_type' => 'App\DelayStep',
            'stepable_id' => DelayStep::create()->id
        ]);

        $firstStep->stepable->update([
            'days_to_wait' => 1
        ]);

        return $firstStep;
    }

    protected function createSecondStep($workflow)
    {
        $secondStep = Step::create([
            'workflow_id' => $workflow->id,
            'stepable_type' => 'App\EmailStep',
            'stepable_id' => EmailStep::create()->id
        ]);

        $secondStep->stepable->update([
            'subject' => 'Welcome to MailerLite',
            'content' => 'email content'
        ]);

        return $secondStep;
    }

    protected function createThirdStep($workflow, $secondStep)
    {
        $workflowActivityEmailAction = WorkflowActivityEmailAction::create([
            'name' => 'was opened'
        ]);

        $workflowActivityCondition = WorkflowActivityCondition::create([
            'workflow_activity_email_id' => $secondStep->stepable->id,
            'workflow_activity_email_action_id' => $workflowActivityEmailAction->id
        ]);

        $thirdStep = Step::create([
            'workflow_id' => $workflow->id,
            'stepable_type' => 'App\ConditionStep',
            'stepable_id' => ConditionStep::create()->id
        ]);

        $thirdStep->stepable->update([
            'conditionable_type' => 'App\WorkflowActivityCondition',
            'conditionable_id' => $workflowActivityCondition->id
        ]);

        return $thirdStep;
    }
}
