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
            $table->string('questiontext');
            $table->string('questiontext_format');
            $table->string('generalfeedback')->nullable();
            $table->string('generalfeedback_format')->nullable();
            $table->float('defaultgrade');
            $table->float('penalty');
            $table->integer('hidden');
            $table->integer('idnumber')->nullable();
            $table->boolean('single');
            $table->boolean('shuffleanswers');
            $table->string('answernumbering')->nullable();
            $table->integer('showstandardinstruction');
            $table->string('correctfeedback')->nullable();
            $table->string('correctfeedback_format')->nullable();
            $table->string('partiallycorrectfeedback')->nullable();
            $table->string('partiallycorrectfeedback_format')->nullable();
            $table->string('incorrectfeedback')->nullable();
            $table->string('incorrectfeedback_format')->nullable();
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
