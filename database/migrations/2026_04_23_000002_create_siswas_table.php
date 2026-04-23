<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('siswas', function (Blueprint $table) {
            $table->id('nis');
            $table->string('nama_siswa');
            $table->string('kelas', 10);
            $table->string('password');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('siswas'); }
};
