<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('assessment_results', function (Blueprint $table) {
            $table->foreignId('term_id')->constrained('terms')->onDelete('restrict')->onUpdate('cascade');
            $table->foreignId('assessment_type_id')->constrained('assessment_types')->onDelete('restrict')->onUpdate('cascade');
            $table->foreignId('academic_session_id')->constrained('academic_sessions')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('assessment_results', function (Blueprint $table) {
            //
        });
    }
}
