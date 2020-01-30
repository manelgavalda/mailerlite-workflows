<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkflowActivityConditionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workflow_activity_conditions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('workflow_activity_email_id');
            $table->unsignedInteger('workflow_activity_email_action_id');
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
        Schema::dropIfExists('workflow_activity_conditions');
    }
}
