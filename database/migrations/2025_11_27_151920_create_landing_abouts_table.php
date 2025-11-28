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
        Schema::create('landing_abouts', function (Blueprint $table) {
            $table->id();

            $table->string('founder_name')->nullable();
            $table->string('founder_position')->nullable();
            $table->text('founder_description')->nullable();
            $table->string('founder_image')->nullable();

            $table->string('leader_name')->nullable();
            $table->string('leader_position')->nullable();
            $table->text('leader_description')->nullable();
            $table->string('leader_image')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('landing_abouts');
    }

};
