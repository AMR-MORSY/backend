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
        Schema::create('action_modification', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("modification_id");
            $table->foreign('modification_id')->references('id')->on('modifications')->cascadeOnUpdate()->cascadeOnDelete();
            $table->unsignedBigInteger("action_id");
            $table->foreign('action_id')->references('id')->on('actions')->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('action_modification');
    }
};
