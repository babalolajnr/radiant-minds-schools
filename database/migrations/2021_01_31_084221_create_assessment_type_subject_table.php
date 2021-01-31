<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssessmentTypeSubjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assessment_type_subject', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_type_id')->constrained('assessment_types')->onDelete('restrict')->onUpdate('cascade');
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
        Schema::dropIfExists('assessment_type_subject');
    }
}
