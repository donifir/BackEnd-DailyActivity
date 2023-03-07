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
        Schema::create('detail_pengingats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pengingat');
            $table->string('nama_kegiatan');
            $table->string('keterangan_kegiatan')->nullable();
            $table->timestampTz('mulai_kegiatan')->nullable();
            $table->boolean('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pengingats');
    }
};
