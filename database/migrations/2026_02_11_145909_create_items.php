<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->string('Site', 8);
            $table->string('ProdGroup', 20);
            $table->string('PType', 7);
            $table->string('ItemCode', 30);
            $table->string('ItemDesc', 50);
            $table->string('UM', 3);
            $table->integer('GSM')->nullable();
            $table->integer('Caliper')->nullable();
            $table->integer('PPR')->nullable();
            $table->integer('Cbnum')->nullable();
            $table->decimal('Width', 20, 4)->nullable();
            $table->decimal('Length', 20, 4)->nullable();
            $table->datetime('CreateDate')->nullable();
            $table->string('CreatedBy', 8)->nullable();

            $table->primary(['Site', 'ItemCode']);

            $table->foreign(['Site', 'PType'])
                ->references(['Site', 'PType'])
                ->on('ptype')
                ->noActionOnDelete()
                ->noActionOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
