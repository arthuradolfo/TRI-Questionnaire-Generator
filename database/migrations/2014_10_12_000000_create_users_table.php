<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
			$table->uuid('id')->unique()->primary();
			$table->uuid('student_id')->index()->nullable();
            $table->string('username');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->float('threshold');
            $table->rememberToken();
            $table->timestamps();
		});

		Schema::table('users', function($table) {
			$table->foreign('student_id')
				->references('id')
				->on('students')
				->cascadeOnUpdate()
				->cascadeOnDelete();
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
