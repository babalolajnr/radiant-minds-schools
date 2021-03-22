<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('sex');
            $table->string('admission_no');
            $table->string('lg');
            $table->string('state');
            $table->string('country');
            $table->date('date_of_birth');
            $table->text('place_of_birth');
            $table->string('blood_group');
            $table->boolean('is_active')->default(false);
            $table->date('graduated_at')->nullable();
            $table->string('image')->nullable()->unique();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('students');
    }
}
