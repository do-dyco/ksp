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
        Schema::table('pinjamans', function (Blueprint $table) {
            $table->text('id_penanggung')->after('status')->nullable();
            $table->text('id_atasan')->after('id_penanggung')->nullable();
            $table->text('id_bendahara')->after('id_atasan')->nullable();
            $table->text('id_admin')->after('id_bendahara')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pinjamans', function (Blueprint $table) {
            $table->dropColumn('id_penanggung');
            $table->dropColumn('id_atasan');
            $table->dropColumn('id_bendahara');
            $table->dropColumn('id_admin');
        });
    }
};
