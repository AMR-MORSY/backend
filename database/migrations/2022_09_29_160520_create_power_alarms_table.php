<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('power_alarms', function (Blueprint $table) {
            $table->id();
            $table->string('zone',50);
            $table->string('operational_zone',100);
            $table->string("area",100);
            $table->string('bsc',100);
            $table->string('site_name',100);
            $table->string('site_code',50);
            $table->string('alarm_name',150);
            $table->date('start_date')->nullable();
            $table->time('start_time')->nullable();
            $table->date('end_date')->nullable();
             $table->time('end_time')->nullable();
            $table->unsignedBigInteger('duration');
            $table->integer('week');
            $table->integer("month");
            $table->integer('year');
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
        Schema::dropIfExists('power_alarms');
    }
};
