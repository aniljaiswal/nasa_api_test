<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNeosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('neos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('reference_id')->index();
            $table->string('name')->index();
            $table->double('speed', 2);
            $table->boolean('is_hazardous')->index()->default(false);
            $table->date('date')->index();
            $table->softDeletes();
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
        Schema::dropIfExists('neos');
    }
}
