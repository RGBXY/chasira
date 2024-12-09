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
        Schema::table('roles', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by')->nullable(); // Menambahkan kolom created_by
            $table->unsignedBigInteger('family_id')->nullable(); // Menambahkan kolom created_by
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null'); // Relasi ke tabel users
            $table->foreign('family_id')->references('id')->on('users')->onDelete('set null'); // Relasi ke tabel users
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn('created_by');
        });
    }
};
