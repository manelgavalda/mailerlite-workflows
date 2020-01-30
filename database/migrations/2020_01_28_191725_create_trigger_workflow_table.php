<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTriggerWorkflowTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trigger_workflow', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('trigger_id');
            $table->unsignedInteger('workflow_id');
            // $table->unsignedInteger('subscriber_group_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trigger_workflow');
    }
}
