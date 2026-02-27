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
        Schema::create('vendors', function (Blueprint $table) {
            $table->string('Site', 8);
            $table->string('Group', 10);
            $table->string('Vendnum', 10);
            $table->string('Name', 255);
            $table->string('Currcode', 3);
            $table->datetime('CreateDate')->nullable();
            $table->string('CreatedBy', 8)->nullable();

            $table->primary(['Site', 'Vendnum']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};
