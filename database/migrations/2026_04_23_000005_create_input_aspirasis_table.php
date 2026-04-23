<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('input_aspirasis', function (Blueprint $table) {
            $table->id('id_pelaporan');
            $table->unsignedBigInteger('nis');
            $table->unsignedBigInteger('id_aspirasi');
            $table->unsignedBigInteger('id_kategori');
            $table->string('lokasi', 100);
            $table->text('ket');
            $table->string('foto')->nullable(); // path foto bukti
            $table->boolean('anonim')->default(false); // laporan anonim
            $table->timestamps();
            $table->foreign('nis')->references('nis')->on('siswas')->onDelete('cascade');
            $table->foreign('id_aspirasi')->references('id_aspirasi')->on('aspirasis')->onDelete('cascade');
            $table->foreign('id_kategori')->references('id_kategori')->on('kategoris')->onDelete('cascade');
        });
    }
    public function down(): void { Schema::dropIfExists('input_aspirasis'); }
};
