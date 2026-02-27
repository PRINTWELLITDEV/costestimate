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
        Schema::create('PaperBoardPricing', function (Blueprint $table) {
            $table->string('Site', 8);
            $table->id();
            $table->string('Group', 10);
            $table->string('PType', 7);
            $table->string('Vendor', 10);
            $table->string('ItemCode', 30);
            $table->string('Currcode', 3);
            $table->decimal('Price_MT', 20, 8)->nullable();
            $table->decimal('Price_Sheet', 20, 8)->nullable();
            $table->decimal('Price_Pound', 20, 8)->nullable();
            $table->decimal('Price_Bale', 20, 8)->nullable();
            $table->datetime('EffectiveDate');
            $table->datetime('CreateDate')->nullable();
            $table->string('CreatedBy', 8)->nullable();

            $table->primary(['Site', 'id']);

            // $table->foreign(['Site', 'PType'])
            //     ->references(['Site', 'PType'])
            //     ->on('ptype')
            //     ->noActionOnDelete();

            $table->foreign(['Site', 'ItemCode'])
                ->references(['Site', 'ItemCode'])
                ->on('items')
                ->noActionOnDelete()
                ->noActionOnUpdate();

            $table->foreign(['Site', 'Vendor'])
                ->references(['Site', 'Vendnum'])
                ->on('vendors')
                ->noActionOnDelete()
                ->noActionOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('PaperBoardPricing');
    }
};
