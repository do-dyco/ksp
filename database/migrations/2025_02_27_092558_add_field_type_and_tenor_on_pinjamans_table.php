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
            $table->text('user_id')->nullable()->change();
            $table->text('type')->after('user_id')->nullable();
            $table->text('tenor')->after('type')->nullable();
            $table->text('total_angsuran')->after('tenor')->nullable();
            $table->text('bunga')->after('total_angsuran')->nullable();
            $table->text('is_approve')->nullable()->change();
            $table->text('status')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pinjamans', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('tenor');
            $table->dropColumn('total_angsuran');
            $table->dropColumn('bunga');
        });
    }
};
