<?php

namespace Tests\Feature;

use App\ActionStep;
use App\ConditionStep;
use App\CopyToAGroupAction;
use App\DelayStep;
use App\EmailStep;
use App\FalseConditionStep;
use App\Group;
use App\Step;
use App\SubscriberJoinTrigger;
use App\Trigger;
use App\TrueConditionStep;
use App\Workflow;
use App\WorkflowActivityCondition;
use App\WorkflowActivityEmailAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

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
        $groupOpened = Group::create(['name' => 'opened']);
        $groupMailerLite = Group::create(['name' => 'MailerLite']);

        $workflow = $this->createWorkflow($groupMailerLite);

        $this->assertEquals(
            $workflow->trigger->value(),
            'When subscriber joins a group MailerLite'
        );

        $firstStep = $this->createFirstStep($workflow);

        $this->assertEquals(
            $firstStep->value(),
            'Wait 1 day(s)'
        );

        $secondStep = $this->createSecondStep($workflow);

        $this->assertEquals(
            $secondStep->value(),
            'Welcome to MailerLite'
        );

        $thirdStep = $this->createThirdStep($workflow, $secondStep->stepable);

        $this->assertEquals(
            $thirdStep->value(),
            'Welcome to MailerLite was opened'
        );

        $trueFourthStep = $this->createTrueStep($workflow, $groupOpened);

        $this->assertEquals(
            $trueFourthStep->value(),
            'Copy to a group opened'
        );

        $falseFourthStep = $this->createFalseStep($workflow);

        $this->assertEquals(
            $falseFourthStep->value(),
            'Wait 5 day(s)'
        );

        $fifthStep = $this->createFifthStep($workflow);

        $this->assertEquals(
            $fifthStep->value(),
            'We miss you!'
        );
    }

    protected function createWorkflow($group)
    {
        $subscriberJoinTrigger = SubscriberJoinTrigger::create([
            'group_id' => $group->id
        ]);

        $trigger = Trigger::create([
            'triggerable_type' => 'App\SubscriberJoinTrigger',
            'triggerable_id' => $subscriberJoinTrigger->id
        ]);

        $workflow = Workflow::create([
            'name' => 'Welcome',
            'trigger_id' => $trigger->id
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

    protected function createThirdStep($workflow, $email)
    {
        $workflowActivityEmailAction = WorkflowActivityEmailAction::create([
            'name' => 'was opened'
        ]);

        $workflowActivityCondition = WorkflowActivityCondition::create([
            'workflow_activity_email_id' => $email->id,
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

    protected function createTrueStep($workflow, $group)
    {
        $trueStep = Step::create([ // parent
            'workflow_id' => $workflow->id,
            'stepable_type' => 'App\TrueConditionStep',
            'stepable_id' => TrueConditionStep::create()->id
        ]);

        $trueFourthStep = Step::create([
            'workflow_id' => $workflow->id,
            'stepable_type' => 'App\ActionStep',
            'stepable_id' => ActionStep::create()->id
        ]);

        $copyToAGroupAction = CopyToAGroupAction::create([
            'group_id' => $group->id
        ]);

        $trueFourthStep->stepable->update([
            'actionable_type' => 'App\CopyToAGroupAction',
            'actionable_id' => $copyToAGroupAction->id
        ]);

        return $trueFourthStep;
    }

    protected function createFalseStep($workflow)
    {
        $falseStep = Step::create([ // parent
            'workflow_id' => $workflow->id,
            'stepable_type' => 'App\FalseConditionStep',
            'stepable_id' => FalseConditionStep::create()->id
        ]);

        $falseFourthStep = Step::create([
            'workflow_id' => $workflow->id,
            'stepable_type' => 'App\DelayStep',
            'stepable_id' => DelayStep::create()->id
        ]);

        $falseFourthStep->stepable->update([
            'days_to_wait' => 5
        ]);

        return $falseFourthStep;
    }

    protected function createFifthStep($workflow)
    {
        $fifthStep = Step::create([
            'workflow_id' => $workflow->id,
            'stepable_type' => 'App\EmailStep',
            'stepable_id' => EmailStep::create()->id
        ]);

        $fifthStep->stepable->update([
            'subject' => 'We miss you!',
            'content' => 'email content'
        ]);

        return $fifthStep;
    }
}
