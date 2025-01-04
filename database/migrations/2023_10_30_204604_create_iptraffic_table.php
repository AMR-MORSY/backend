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
        Schema::create('iptraffic', function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->string("site_code",50);
            $table->foreign('site_code')->references('site_code')->on('sites')->cascadeOnUpdate()->cascadeOnDelete();
            $table->date("reporting_date");
            $table->date("clearance_date")->nullable();
            $table->string("network_element",100);
            $table->string("far_end",100);
            $table->enum("office",["Maadi","Shrouk","New Cairo","Haram","Gisr El Suez","Shoubra","Mohandseen","October","Helwan","New Capital","Nasr City"]);
            $table->enum("status",["Solved","Pending"]);
            $table->string("ATST_feedback",200)->nullable();
            $table->string("maintenance_feedback",200)->nullable();
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
        Schema::dropIfExists('iptraffic');
    }
};
