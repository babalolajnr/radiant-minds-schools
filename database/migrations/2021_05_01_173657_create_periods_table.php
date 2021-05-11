<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeriodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('periods', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->foreignId('academic_session_id')->constrained('academic_sessions')->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('term_id')->constrained('terms')->onUpdate('cascade')->onDelete('restrict');
            $table->date('start_date')->unique();
            $table->date('end_date')->unique();
            $table->boolean('active')->unique()->nullable();
            $table->bigInteger('rank')->unique();
            $table->integer('no_times_school_opened')->nullable();
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
        Schema::dropIfExists('periods');
    }
}
