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
        Schema::create('modifications', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string('site_code');
            $table->foreign('site_code')->references('site_code')->on('sites')->cascadeOnUpdate()->cascadeOnDelete();
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->string('site_name');
            $table->enum('subcontractor',['','OT','Alandick','Tri-Tech','Siatnile','Merc','GP','Systel','MBV','TELE-TECH','SAG','LM',"Red Tech","HAS","MERG","STEPS","H-PLUS","GTE",
            "AFRO",
            "Benaya",
            "EEC",
            "Egypt Gate",
            "Huawei",
            "INTEGRA",
            "Unilink",]);
            $table->enum('requester',['','Management Team','Civil Team','Maintenance','Radio','Rollout','Transmission','GA','Soc','Sharing team']);
            $table->enum("actions",["Retrofitting","Antenna Swap","Repair","Adding SA","Change Power Cable","WE Sharing Panel","PT Ring","Adding Antennas","Extending Cables","Concrete Works","Cable Trays"]);
            $table->text('description');
            $table->enum('status',['in progress','done','waiting D6'])->default("in progress");
            $table->string("oz",50)->nullable();
            $table->unsignedBigInteger('action_owner');
            $table->foreign('action_owner')->references('id')->on('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->enum('project',['Site Dismantle','NTRA','Unsafe Existing','B2B','LTE','5G','Sharing','Site Security','Adding Sec','TDD',"Power Modification","L1 Modification","Tx Modification","G2G","New Sites"])->default('Tx Modification');
            $table->date('request_date');
            $table->date('cw_date')->nullable();
            $table->date('d6_date')->nullable();
            $table->string('wo_code',20)->nullable();
            $table->decimal('est_cost', $precision = 8, $scale = 2)->nullable();
            $table->decimal('final_cost', $precision = 8, $scale = 2)->nullable();
            $table->integer("reported")->default(0);
            $table->date('reported_at')->nullable();
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
        Schema::dropIfExists('modifications');
    }
};
