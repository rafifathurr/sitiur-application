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
        Schema::table('giat_kampung_tertib', function (Blueprint $table) {
            $table->dropColumn('number_giat');
        });

        Schema::table('giat_anev', function (Blueprint $table) {
            $table->dropColumn('number_giat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
