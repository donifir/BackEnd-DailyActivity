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
        Schema::create('pengingats', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pengingat');
            $table->string('keterangan_pengingat');
            $table->timestamp('mulai_pengingat')->nullable();
            $table->timestamp('selesai_pengingat')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengingats');
    }
};
