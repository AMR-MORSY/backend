<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('batteries', function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->string("site_code",50);
            $table->foreign('site_code')->references('site_code')->on('sites')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string("batteries_brand",50);
            $table->date("installation_date")->nullable();
            $table->integer("no_strings");
            $table->enum("category",["New","Tested","Used"])->default("New");
            $table->string("stock",50)->nullable();
            $table->string("comment",250)->nullable();
            $table->date("theft_case")->nullable();
            $table->string("batteries_status",50)->nullable();
            $table->string("battery_volt",50)->nullable();
            $table->string("battery_amp_hr",50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batteries');
    }
};
