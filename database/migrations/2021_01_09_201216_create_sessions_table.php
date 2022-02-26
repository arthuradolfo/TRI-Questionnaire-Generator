<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sessions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignUuid('student_id')
                ->references('id')
                ->on('students')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignUuid('category_id')
                ->references('id')
                ->on('categories')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->uuid('current_answer_id')->nullable();
            $table->integer("tqg_id");
            $table->integer("number_questions");
            $table->float("standard_error");
            $table->integer("status");
            $table->uuid('current_question')->nullable();
            $table->text("questions");
            $table->integer("questions_usage")->nullable();
            $table->integer("slot")->nullable();
            $table->timestamp("time_started");
            $table->timestamp("time_finished")->nullable();
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
        Schema::dropIfExists('sessions');
    }
}
