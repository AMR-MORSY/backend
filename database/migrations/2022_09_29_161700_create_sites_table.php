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
        Schema::create('sites', function (Blueprint $table) {
            $table->id();
            $table->string("site_code",20)->unique();
            $table->string("site_name",50);
            $table->string("BSC",50)->nullable();
            $table->string("RNC",50)->nullable();
            $table->string('office',50)->nullable();
            $table->enum('type',["Outdoor",'Shelter','Micro','Pico','Mobile Station','LDN'])->nullable();
            $table->enum('category',['Normal','BSC',"VIP","NDL","LDN","VIP + NDL"])->nullable();
            $table->enum('severity',['Bronze',"Golden","Silver"])->nullable();
            $table->enum('sharing',['Yes',"No"])->nullable();
            $table->enum('host',['VF',"OG","ET","WE"])->nullable();
            $table->enum('gest',['VF',"OG","ET","WE","ET+VF","ET+VF+WE","ET+WE","VF+WE",'OG-Power-Only','VF-Power-Only','ET-Power-Only','WE-Power-Only'])->nullable();
            $table->string("vf_code",50)->nullable();
            $table->string("et_code",50)->nullable();
            $table->string("we_code",50)->nullable();
            $table->enum('oz',['Cairo South',"Giza","Cairo North",'Cairo East','North Upper','Red Sea','South Upper','Sinai','ALEX','NORTH COAST','Delta South','Delta North'])->nullable();
            $table->enum('zone',["Cairo"])->nullable();
            $table->integer("2G_cells")->nullable();
            $table->integer("3G_cells")->nullable();
            $table->integer("4G_cells")->nullable();
            $table->enum("status",["On Air","Off Air"])->default("On Air");
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
        Schema::dropIfExists('sites');
    }
};
