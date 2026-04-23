<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('kategoris', function (Blueprint $table) {
            $table->id('id_kategori');
            $table->string('ket_kategori', 50);
            // jenis: sarana_prasarana, kesejahteraan_siswa
            $table->enum('jenis', ['sarana_prasarana', 'kesejahteraan_siswa']);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('kategoris'); }
};
