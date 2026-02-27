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
        Schema::create('u_m', function (Blueprint $table) {
            // $table->id();
            // $table->timestamps();
            $table->string('UM', 3)->primary();
            $table->string('UMDesc', 50)->nullable();
            $table->datetime('CreateDate')->nullable();
            $table->string('CreatedBy', 8)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('u_m');
    }
};
