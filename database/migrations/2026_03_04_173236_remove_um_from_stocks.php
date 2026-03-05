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
        Schema::table('stocks', function (Blueprint $table) {
            $table->dropColumn('UM');
        });

        Schema::table('PaperBoardPricing', function (Blueprint $table) {
            $table->string('UM', 3)->nullable()->after('Currcode');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stocks', function (Blueprint $table) {
            $table->string('UM', 3)->nullable();
        });

        Schema::table('PaperBoardPricing', function (Blueprint $table) {
            $table->dropColumn('UM');
        });
    }
};
