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
        Schema::create('instruments', function (Blueprint $table) {
            $table->id();
            $table->string('site_code');
            $table->foreign("site_code")->references("site_code")->on('sites')->onUpdate("cascade")->onDelete("cascade");
            $table->date("on_air_date")->nullable();
            $table->string("topology")->nullable();
            $table->boolean("ntra_cluster")->default(0);
            $table->boolean("care_ceo")->default(0);
            $table->boolean("axsees")->default(0);
            $table->boolean("serve_compound")->default(0);
            $table->integer("no_ldn_accounts")->default(0);
            $table->integer("no_tp_accounts")->default(0);
            $table->string("power_source",50)->nullable();
            $table->string("power_meter_type",50)->nullable();
            $table->string("power_cable_cross_sec",50)->nullable();
            $table->string("power_cable_length",50)->nullable();
            $table->string("gen_capacity",50)->nullable();
            $table->integer("no_bts")->default(0);
            $table->integer("mrfu_2G")->default(0);
            $table->integer("mrfu_3G")->default(0);
            $table->integer("mrfu_4G")->default(0);
            $table->boolean("tdd")->default(0);
            $table->integer("no_mw")->default(0);
            $table->string("mw_type",50)->nullable();
            $table->boolean("eband")->default(0);
            $table->string("ac1_type",50)->nullable();
            $table->string("ac1_hp",50)->nullable();
            $table->string("ac2_type",50)->nullable();
            $table->string("ac2_hp",50)->nullable();
            $table->string("network_type",50)->nullable();
            $table->string("rec_brand",50)->nullable();
            $table->string("module_capacity",50)->nullable();
            $table->string("no_module",50)->nullable();
            $table->string("pld_value")->nullable();
            $table->boolean("net_eco")->default(0);
            $table->string("net_eco_activation",50)->nullable();
            $table->string("battery_brand",50)->nullable();
            $table->string("battery_volt",50)->nullable();
            $table->string("battery_amp_hr",50)->nullable();
            $table->string("no_strings",50)->nullable();
            $table->string("no_batteries",50)->nullable();
            $table->string("batteries_status",50)->nullable();
            $table->date("last_pm_date",50)->nullable();
            $table->integer("overhaul_power_consumption")->default(0);
            $table->boolean("need_access_permission")->default(0);
            $table->string("permission_type",50)->nullable();
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
        Schema::dropIfExists('instruments');
    }
};
