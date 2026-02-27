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
        Schema::create('ptype', function (Blueprint $table) {
            $table->string('Site', 8);
            $table->string('PType', 7);
            $table->string('PTypeDesc', 40);
            $table->string('DescLabel', 40);
            $table->datetime('CreateDate')->nullable();
            $table->string('CreatedBy', 8)->nullable();

            $table->primary(['Site', 'PType']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ptype');
    }
};
