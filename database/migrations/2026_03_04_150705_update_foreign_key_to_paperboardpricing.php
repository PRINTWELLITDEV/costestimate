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
        Schema::table('paperboardpricing', function (Blueprint $table) {
            //
            $table->dropForeign(['Site', 'ItemCode']);

            $table->renameColumn('ItemCode', 'StockCode');
        });

        Schema::table('paperboardpricing', function (Blueprint $table) {

            // 3️⃣ Add new foreign key
            $table->foreign(['Site', 'StockCode'])
                ->references(['Site', 'StockCode'])
                ->on('stocks')
                ->noActionOnDelete()
                ->noActionOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('paperboardpricing', function (Blueprint $table) {

            $table->dropForeign(['Site', 'StockCode']);

            $table->renameColumn('StockCode', 'ItemCode');
        });

        Schema::table('paperboardpricing', function (Blueprint $table) {

            $table->foreign(['Site', 'ItemCode'])
                ->references(['Site', 'ItemCode'])
                ->on('items')
                ->noActionOnDelete()
                ->noActionOnUpdate();
        });
    }
};
