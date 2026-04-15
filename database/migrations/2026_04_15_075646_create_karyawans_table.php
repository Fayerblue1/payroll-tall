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
        Schema::create('karyawan', function (Blueprint $table) {
            $table->id();

            //Data Karyawan
            $table->string('mik')->unique();
            $table->string('nama');
            $table->string('email')->unique();
            $table->string('telepon');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->date('tanggal_masuk');

            //Relasi
            $table->foreignId('departemen_id')->constrained('departemen')->cascadeOnDelete();
            $table->foreignId('jabatan_id')->constrained('jabatan')->cascadeOnDelete();

            //Finansial
            $table->integer('gaji_pokok');
            $table->integer('tunjangan')->default(0);

            //Administrasi
            $table->string('status')->default('Aktif'); //Aktif, Cuti, Resign
            $table->string("bank");
            $table->string("no_rekening");
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karyawan');
    }
};
