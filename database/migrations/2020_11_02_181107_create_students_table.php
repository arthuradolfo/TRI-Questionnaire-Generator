<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->bigInteger('moodle_id')->nullable();
            $table->foreignUuid('user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->string('username');
            $table->string('email')->unique();
            $table->string('firstname');
            $table->string('lastname');
            $table->string('idnumber')->nullable();
            $table->string('institution');
            $table->string('department');
            $table->string('phone1');
            $table->string('phone2');
            $table->string('city');
            $table->string('url');
            $table->string('icq');
            $table->string('skype');
            $table->string('aim');
            $table->string('yahoo');
            $table->string('msn');
            $table->string('country');
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
        Schema::dropIfExists('students');
    }
}
