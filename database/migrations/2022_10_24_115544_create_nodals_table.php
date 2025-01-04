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
        Schema::create('nodals', function (Blueprint $table) {
            $table->id();
            // $table->string('nodal_code',50)->unique();
            $table->string('nodal_code',50);
            $table->foreign('nodal_code')->references("site_code")->on('sites')->onUpdate("cascade")->onDelete("cascade");
            $table->string('nodal_name',200);
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
        Schema::dropIfExists('nodals');
    }
};
