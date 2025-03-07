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
        Schema::table('users', function (Blueprint $table) {
            $table->string('nip')->after('name')->nullable();
            $table->date('tgl_masuk')->after('nip')->nullable();
            $table->string('dept')->after('tgl_masuk')->nullable();
            $table->string('alamat')->after('dept')->nullable();
            $table->string('no_telp')->after('alamat')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('nip');
                $table->dropColumn('tgl_masuk');
                $table->dropColumn('dept');
                $table->dropColumn('alamat');
                $table->dropColumn('no_telp');
            });
    }
};
