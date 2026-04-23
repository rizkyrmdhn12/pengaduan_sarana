<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('nama');
            $table->string('password');
            // role: kepala_sekolah, guru, sapras, kesiswaan, guru_bk
            $table->enum('role', ['kepala_sekolah','guru','sapras','kesiswaan','guru_bk']);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('admins'); }
};
