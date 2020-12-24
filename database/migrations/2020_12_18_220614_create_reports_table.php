<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->integer('lessons')->nullable();
            $table->integer('wage')->nullable();
            $table->integer('profit')->nullable();
            $table->integer('lessons_count')->nullable();
            $table->integer('students_count')->nullable();
            $table->integer('pass_count')->nullable();
            $table->integer('pass_notpayed_count')->nullable();
            $table->integer('pays_in')->nullable();
            $table->integer('pays_out')->nullable();
            $table->integer('pays_profit')->nullable();
            $table->string('period');
            $table->string('type');
            $table->string('errors')->nullable();
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
        Schema::dropIfExists('reports');
    }
}
