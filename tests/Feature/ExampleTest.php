<?php

namespace Tests\Feature;

use App\Action;
use App\Condition;
use App\ConditionStep;
use App\ConditionTrigger;
use App\ConditionType;
use App\DelayStep;
use App\EmailStep;
use App\Step;
use App\StepType;
use App\SubscriberGroup;
use App\Trigger;
use App\TriggerField;
use App\Workflow;
use App\WorkflowActivityCondition;
use App\WorkflowActivityEmailAction;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        // $triggers = Trigger::insert([
        //     [
        //         'icon' => 'icon',
        //         'name' => 'When subscriber clicks a link',
        //         'description' => 'Workflow triggered when a subscriber clicks a link in any campaign or automation workflow'
        //     ],
        //     [
        //         'icon' => 'icon',
        //         'name' => 'Updated field',
        //         'description' => 'Workflow triggered when a subscriber click a link in any campaign or automation workflow'
        //     ],
        //     [
        //         'icon' => 'icon',
        //         'name' => 'The anniversary of a date',
        //         'description' => 'Workflow triggered on a specific date (great for subscriptions, free trials, etc.)'
        //     ]
        // ]);

        // $completesFormTrigger = Trigger::create([
        //     'icon' => 'icon',
        //     'name' => 'When subscriber completes a form',
        //     'description' => 'Workflow triggered when a person subscribes to a form.'
        // ]);

        // // $subscriberGroup = SubscriberGroup::create([
        // //     'name' => 'MailerLite'
        // // ]);

        // // $emailStepType = StepType::create([
        // //     'name' => 'Email',
        // //     'icon' => 'email'
        // // ]);

        // // $delayStepType = StepType::create([
        // //     'name' => 'delay',
        // //     'icon' => 'time'
        // // ]);

        // // $conditionStepType = StepType::create([
        // //     'name' => 'condition',
        // //     'icon' => 'tree'
        // // ]);

        // // $actionStepType = StepType::create([
        // //     'name' => 'action',
        // //     'icon' => 'gear'
        // // ]);

        // $campaingActivitycondition = Condition::create([
        //     'name' => 'Campaign activity'
        // ]);

        // $workflowActivitycondition = Condition::create([
        //     'name' => 'Wokflow activity'
        // ]);

        // $wasOpenedConditionTrigger = ConditionTrigger::create([
        //     'name' => 'was opened'
        // ]);

        // $copyToAGroupAction = Action::create([
        //     'name' => 'Copy to a group'
        // ]);











        // Setup

        $subscriberJoinTrigger = Trigger::create([
            'icon' => 'icon',
            'name' => 'When subscriber joins a group',
            'description' => 'Workflow triggered when a subscriber joins your subscriber group.'
        ]);

        $subscriberJoinTrigger->field()->create([
            'name' => 'SubscriberGroup',
            'type' => 'select'
        ]);

        // Workflow

        $workflow = Workflow::create([
            'name' => 'Welcome',
            'trigger_id' => $subscriberJoinTrigger->id
        ]);

        $workflow->trigger->field->update([
            'value' => 'MailerLite'
        ]);

        $this->assertEquals(
            $workflow->trigger->value(),
            'When subscriber joins a group MailerLite'
        );


        // Step 1

        $delayStep = DelayStep::create();

        $firstStep = Step::create([
            'workflow_id' => $workflow->id,
            'stepable_type' => 'App\DelayStep',
            'stepable_id' => $delayStep->id
        ]);

        $firstStep->stepable->update([
            'days_to_wait' => 1
        ]);

        $this->assertEquals(
            $firstStep->stepable->value(),
            'Wait 1 day(s)'
        );


        // Step 2

        $emailStep = EmailStep::create();

        $secondStep = Step::create([
            'workflow_id' => $workflow->id,
            'stepable_type' => 'App\EmailStep',
            'stepable_id' => $emailStep->id
        ]);

        $secondStep->stepable->update([
            'subject' => 'Welcome to MailerLite',
            'content' => 'email content'
        ]);

        $this->assertEquals(
            $secondStep->stepable->value(),
            'Welcome to MailerLite'
        );











        // Step 3
        $conditionType = ConditionType::create([
            'name' => 'Workflow activity',
        ]);

        $workflowActivityEmailAction = WorkflowActivityEmailAction::create([
            'name' => 'was opened'
        ]);


        $conditionStep = ConditionStep::create();

        $thirdStep = Step::create([
            'workflow_id' => $workflow->id,
            'stepable_type' => 'App\ConditionStep',
            'stepable_id' => $conditionStep->id
        ]);

        $workflowActivityCondition = WorkflowActivityCondition::create([
            'workflow_activity_email_id' => $secondStep->stepable->id,
            'workflow_activity_email_action_id' => $workflowActivityEmailAction->id
        ]);

        $conditionStep->update([
            'condition_type_id' => $conditionType->id,
            'conditionable_type' => 'App\WorkflowActivityCondition',
            'conditionable_id' => $workflowActivityCondition->id

        ]);

        $this->assertEquals(
            $thirdStep->stepable->value(),
            'Welcome to MailerLite was opened'
        );



        // $condition = Condition::create([
        //     'condition_step_id' => $conditionStep->id,
        //     'condition_type' => $conditionType->id,
        //     'conditionable_type' => 'App\WorkflowActivityCondition',
        //     'conditionable_id' => $workflowActivityCondition->id,
        // ]);







        // $thirdStep->stepable->update([
        //     'condition_id' => $workflowActivityCondition->id,
        //     'condition_action_id' => workflowActivityEmailAction->id
        // ]);

        // $this->assertEquals(
        //     $thirdStep->stepable->value(),
        //     'Welcome to MailerLite'
        // );
















        // $secondStep = Step::create([
        //     'workflow_id' => $workflow->id,
        //     'parent_id' => $firstStep->id,
        //     'step_type_id' => $emailStepType->id,
        //     // 'content' => 'Welcome to MailerLite'
        // ]);

        // $thirdStep = Step::create([
        //     'workflow_id' => $workflow->id,
        //     'parent_id' => $secondStep->id,
        //     'step_type_id' => $conditionStepType->id,
        //     // 'condition_id' => $workflowActivitycondition->id,
        //     // 'condition_target_id' => $secondStep->id,
        //     // 'condition_trigger_id' => $wasOpenedConditionTrigger->id
        // ]);

        // $fourthStepTrue = Step::create([
        //     'workflow_id' => $workflow->id,
        //     'parent_id' => $secondStep->id,
        //     'step_type_id' => $actionStepType->id
        //     // 'action_id' => $copyToAGroupAction->id
        //     // 'action_group_id' => $openedGroupAction->id
        // ]);

        $this->assertTrue(true);
    }
}
