<?php

namespace Tests\Feature;

use App\Step;
use App\Group;
use App\Trigger;
use App\DelayStep;
use App\EmailStep;
use App\ActionStep;
use Tests\TestCase;
use App\ConditionStep;
use App\TrueConditionStep;
use App\CopyToAGroupAction;
use App\FalseConditionStep;
use App\SubscriberJoinTrigger;
use App\WorkflowActivityCondition;
use App\WorkflowActivityEmailAction;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WorkFlowTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->groupOpened = Group::create(['name' => 'opened']);
        $this->groupMailerLite = Group::create(['name' => 'MailerLite']);
    }

    /** @test */
    public function a_workflow_can_be_created()
    {
        $workflow = $this->createWorkflow();
        $this->assertEquals($workflow->trigger->value(), 'When subscriber joins a group MailerLite');

        $firstStep = $this->createFirstStep($workflow);
        $this->assertTrue($firstStep->parentable->is($workflow));
        $this->assertEquals($firstStep->value(), 'Wait 1 day(s)');

        $secondStep = $this->createSecondStep($firstStep);
        $this->assertTrue($secondStep->parentable->is($firstStep));
        $this->assertEquals($secondStep->value(), 'Welcome to MailerLite');

        $thirdStep = $this->createThirdStep($secondStep);
        $this->assertTrue($thirdStep->parentable->is($secondStep));
        $this->assertEquals($thirdStep->value(), 'Welcome to MailerLite was opened');

        $trueConditional = $this->createTrueConditional($thirdStep);
        $this->assertTrue($trueConditional->parentable->is($thirdStep));

        $trueStep = $this->createTrueStep($trueConditional);
        $this->assertTrue($trueStep->parentable->is($trueConditional));
        $this->assertEquals($trueStep->value(), 'Copy to a group opened');

        $falseConditional = $this->createFalseConditional($thirdStep);
        $this->assertTrue($falseConditional->parentable->is($thirdStep));

        $falseStep = $this->createFalseStep($falseConditional);
        $this->assertTrue($falseStep->parentable->is($falseConditional));
        $this->assertEquals($falseStep->value(), 'Wait 5 day(s)');

        $fifthStep = $this->createFifthStep($falseStep);
        $this->assertTrue($fifthStep->parentable->is($falseStep));
        $this->assertEquals($fifthStep->value(), 'We miss you!');
    }

    protected function createWorkflow()
    {
        $subscriberJoinTrigger = SubscriberJoinTrigger::create([
            'group_id' => $this->groupMailerLite->id
        ]);

        return Trigger::create([
            'triggerable_type' => 'App\SubscriberJoinTrigger',
            'triggerable_id' => $subscriberJoinTrigger->id
        ])->workflow()
            ->create(['name' => 'Welcome']);
    }

    protected function createFirstStep($workflow)
    {
        $delayStep = DelayStep::create([
            'time_to_wait' => 1,
            'time_unit' => 'day'
        ]);

        return Step::create([
            'parentable_type' => 'App\Workflow',
            'parentable_id' => $workflow->id,
            'stepable_type' => 'App\DelayStep',
            'stepable_id' => $delayStep->id
        ]);
    }

    protected function createSecondStep($firstStep)
    {
        $emailStep = EmailStep::create([
            'subject' => 'Welcome to MailerLite',
            'content' => 'email content'
        ]);

        return Step::create([
            'parentable_type' => 'App\Step',
            'parentable_id' => $firstStep->id,
            'stepable_type' => 'App\EmailStep',
            'stepable_id' => $emailStep->id
        ]);
    }

    protected function createThirdStep($secondStep)
    {
        $workflowActivityEmailAction = WorkflowActivityEmailAction::create([
            'name' => 'was opened'
        ]);

        $workflowActivityCondition = WorkflowActivityCondition::create([
            'workflow_activity_email_id' => $secondStep->stepable->id,
            'workflow_activity_email_action_id' => $workflowActivityEmailAction->id
        ]);

        $conditionStep = ConditionStep::create([
            'conditionable_type' => 'App\WorkflowActivityCondition',
            'conditionable_id' => $workflowActivityCondition->id
        ]);

        return Step::create([
            'parentable_type' => 'App\Step',
            'parentable_id' => $secondStep->id,
            'stepable_type' => 'App\ConditionStep',
            'stepable_id' => $conditionStep->id
        ]);
    }

    protected function createTrueConditional($thirdStep)
    {
        return Step::create([
            'parentable_type' => 'App\Step',
            'parentable_id' => $thirdStep->id,
            'stepable_type' => 'App\TrueConditionStep',
            'stepable_id' => TrueConditionStep::create()->id
        ]);
    }

    protected function createFalseConditional($thirdStep)
    {
        return Step::create([
            'parentable_type' => 'App\Step',
            'parentable_id' => $thirdStep->id,
            'stepable_type' => 'App\FalseConditionStep',
            'stepable_id' => FalseConditionStep::create()->id
        ]);
    }

    protected function createTrueStep($trueConditional)
    {
        $copyToAGroupAction = CopyToAGroupAction::create([
            'group_id' => $this->groupOpened->id
        ]);

        $actionStep = ActionStep::create([
            'actionable_type' => 'App\CopyToAGroupAction',
            'actionable_id' => $copyToAGroupAction->id
        ]);

        return Step::create([
            'parentable_type' => 'App\Step',
            'parentable_id' => $trueConditional->id,
            'stepable_type' => 'App\ActionStep',
            'stepable_id' => $actionStep->id
        ]);
    }

    protected function createFalseStep($falseConditional)
    {
        $delayStep = DelayStep::create([
            'time_to_wait' => 5,
            'time_unit' => 'day'
        ]);

        return Step::create([
            'parentable_type' => 'App\Step',
            'parentable_id' => $falseConditional->id,
            'stepable_type' => 'App\DelayStep',
            'stepable_id' => $delayStep->id
        ]);
    }

    protected function createFifthStep($falseStep)
    {
        $emailStep = EmailStep::create([
            'subject' => 'We miss you!',
            'content' => 'email content'
        ]);

        return Step::create([
            'parentable_type' => 'App\Step',
            'parentable_id' => $falseStep->id,
            'stepable_type' => 'App\EmailStep',
            'stepable_id' => $emailStep->id
        ]);
    }
}
