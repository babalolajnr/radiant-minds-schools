<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcademicSessionTermTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('academic_session_term', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->foreignId('academic_session_id')->constrained('academic_sessions')->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('term_id')->constrained('terms')->onUpdate('cascade')->onDelete('restrict');
            $table->date('start_date')->unique();
            $table->date('end_date')->unique();
            $table->boolean('active')->unique()->nullable();
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
        Schema::dropIfExists('academic_session_term');
    }
}
