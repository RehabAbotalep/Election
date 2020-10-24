<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElectorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('electors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->bigInteger('constituency_id')->unsigned();
            $table->bigInteger('area_id')->unsigned();
            $table->bigInteger('job_id')->unsigned();
            $table->enum('gender', ['0', '1']);
            $table->bigInteger('registeration_number');
            $table->date('registeration_date');
            $table->bigInteger('unified_number');
            $table->date('birth_date');
            $table->foreign('constituency_id')->references('id')->on('constituencies')->onDelete('cascade');
            $table->foreign('area_id')->references('id')->on('areas')->onDelete('cascade');
            $table->foreign('job_id')->references('id')->on('jobs')->onDelete('cascade');
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
        Schema::dropIfExists('electors');
    }
}
