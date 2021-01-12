<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassroomSubjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('classroom_subject', function (Blueprint $table) {
            $table->id();
            $table->foreignId('classroom_id')->constrained('classrooms')->onDelete('restrict')->onUpdate('cascade');
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('restrict')->onUpdate('cascade');
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
        Schema::dropIfExists('classroom_subject');
    }
}
