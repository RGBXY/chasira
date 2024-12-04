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
            $table->string('parent_id')->nullable();
            $table->unsignedBigInteger("outlet_id")->nullable();
            $table->unsignedBigInteger("role_id")->nullable();
            $table->string("status")->nullable();
            $table->foreign('outlet_id')->references('id')->on('outlets')->onDelete('set null'); 
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('set null'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
