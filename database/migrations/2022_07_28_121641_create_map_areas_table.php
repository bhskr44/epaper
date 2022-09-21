<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('map_areas', function (Blueprint $table) {
            $table->id()->unique();
            $table->string('date');
            $table->string('paperId');
            $table->string('edition');
            $table->string('pageNo');
            $table->string('description');
            $table->string('connection')->nullable();
            $table->string('x1');
            $table->string('y1');
            $table->string('x2');
            $table->string('y2');
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
        Schema::dropIfExists('map_areas');
    }
};
