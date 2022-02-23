<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->bigInteger('moodle_id')->nullable();
            $table->foreignUuid('category_id')
                ->references('id')
                ->on('categories')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignUuid('user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->string('type');
            $table->string('name');
            $table->longText('questiontext');
            $table->string('questiontext_format');
            $table->string('generalfeedback')->nullable();
            $table->string('generalfeedback_format')->nullable();
            $table->float('defaultgrade')->nullable();
            $table->float('penalty')->nullable();
            $table->integer('hidden')->nullable();
            $table->string('idnumber')->nullable();
            $table->boolean('single')->nullable();
            $table->boolean('shuffleanswers')->nullable();
            $table->string('answernumbering')->nullable();
            $table->integer('showstandardinstruction')->nullable();
            $table->string('correctfeedback')->nullable();
            $table->string('correctfeedback_format')->nullable();
            $table->string('partiallycorrectfeedback')->nullable();
            $table->string('partiallycorrectfeedback_format')->nullable();
            $table->string('incorrectfeedback')->nullable();
            $table->string('incorrectfeedback_format')->nullable();
            $table->float('ability');
            $table->float('discrimination');
            $table->float('guess');
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
        Schema::dropIfExists('questions');
    }
}
