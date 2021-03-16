<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateADSTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('a_d_s', function (Blueprint $table) {
            $table->id();
            $table->enum('value', [1,2,3,4,5]);
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('academic_session_id')->constrained('academic_sessions')->onDelete('restrict')->onUpdate('cascade');
            $table->foreignId('term_id')->constrained('terms')->onDelete('restrict')->onUpdate('cascade');
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
        Schema::dropIfExists('a_d_s');
    }
}
