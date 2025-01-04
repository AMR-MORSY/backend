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
        Schema::create('2g_nurs', function (Blueprint $table) {
            $table->id();
            $table->enum("work_group",["ALGAM","HLGAM","NLGAM"]);
            $table->string('Action_OGS_responsible')->nullable();
            $table->text('impacted_sites');
            $table->string('BSC')->nullable();
            $table->integer('cells');
            $table->dateTime("begin");
            $table->dateTime("end");
            $table->decimal("nur", $precision = 8, $scale = 2);
            $table->string("system");
            $table->string("sub_system");
            $table->text('solution')->nullable();
            $table->string("oz", 100);
            $table->enum('type', ['Involuntary', 'Voluntary']);
            $table->boolean("access")->default(0)->nullable();
            $table->boolean('Force_Majeure')->nullable()->default(0);
            $table->string('Force_Majeure_type')->nullable();
            $table->enum("technology", ['2G'])->default("2G");
            $table->string('Dur_Hr');
            $table->integer('Dur_min');
            $table->integer("week");
            $table->integer("year");
            $table->decimal("nur_c", $precision = 8, $scale = 2);
            $table->integer('network_cells_2G');
            $table->integer('total_network_cells');
            $table->string("gen_owner")->nullable();
            $table->string('problem_site_code', 100)->nullable();
            $table->string('problem_site_name', 300)->nullable();
            $table->integer('month');
            $table->decimal('monthly_nur', $precision = 8, $scale = 2);
            $table->string("office",50)->nullable();
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
        Schema::dropIfExists('nurs');
    }
};
