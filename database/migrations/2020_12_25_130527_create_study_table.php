<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('study', function (Blueprint $table) {
            $table->id();
            $table->integer('uuid')->nullable()->unique();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('observe')->nullable();
            $table->string('subject_name');
            $table->string('subject_title');
            $table->string('site')->nullable();
            $table->string('department');
            $table->integer('date');
            $table->timestamp('shift_start_at')->nullable();
            $table->timestamp('shift_end_at')->nullable();
            $table->integer('shift_duration');
            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_at')->nullable();
            $table->integer('duration')->nullable();
            $table->longText('findings')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('study');
    }
}
