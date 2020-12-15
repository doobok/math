<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLessonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->timestamp('start');
            $table->timestamp('end')->nullable();
            $table->string('color')->nullable();
            $table->integer('price_student')->nullable();
            $table->integer('price_tutor')->nullable();
            $table->integer('tutor_id')->nullable();
            $table->integer('classroom_id')->nullable();
            $table->json('students');
            $table->boolean('paid')->default(false);
            $table->boolean('timed')->default(true);
            $table->timestamp('period_end')->nullable();
            $table->string('comment')->nullable();
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
        Schema::dropIfExists('lessons');
    }
}
