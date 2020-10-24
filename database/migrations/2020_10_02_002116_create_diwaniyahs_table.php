<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiwaniyahsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diwaniyahs', function (Blueprint $table) {
            $table->id();
            $table->string('owner');
            $table->string('occasion');
            $table->string('region');
            $table->string('address');
            $table->date('date');
            $table->string('person');
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
        Schema::dropIfExists('diwaniyahs');
    }
}
