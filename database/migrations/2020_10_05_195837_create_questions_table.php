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
            $table->string('type');
            $table->string('name');
            $table->string('questiontext');
            $table->string('questiontext_format');
            $table->string('generalfeedback');
            $table->string('generalfeedback_format');
            $table->float('defaultgrade');
            $table->float('penalty');
            $table->integer('hidden');
            $table->integer('idnumber')->nullable();
            $table->boolean('single');
            $table->boolean('shuffleanswers');
            $table->string('answernumbering');
            $table->integer('showstandardinstruction');
            $table->string('correctfeedback');
            $table->string('correctfeedback_format');
            $table->string('partiallycorrectfeedback');
            $table->string('partiallycorrectfeedback_format');
            $table->string('incorrectfeedback');
            $table->string('incorrectfeedback_format');
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
