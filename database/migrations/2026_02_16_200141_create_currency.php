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
        Schema::create('currency', function (Blueprint $table) {
            $table->string('Currcode', 3);
            $table->string('CurrDesc', 50);
            $table->datetime('CreateDate')->nullable();
            $table->string('CreatedBy', 8)->nullable();

            $table->primary('Currcode');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('currency');
    }
};
