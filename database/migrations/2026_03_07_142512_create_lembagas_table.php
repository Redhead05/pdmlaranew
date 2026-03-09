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
        Schema::create('lembagas', function (Blueprint $table) {
            $table->id();
            $table->string('npsn', 20)->unique();
            $table->string('satuan_pen');
            $table->text('alamat')->nullable();
            $table->string('kelurahan')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('kabupaten')->nullable();
            $table->string('status'); // Negeri/Swasta
            $table->enum('jenjang', ['paud', 'dikmas', 'dikdas', 'dikmen', 'kesetaraan']);
            $table->enum('bentuk_pendidikan',
                ['KB','Nava Dhamasekha','PAUDQ', 'Pratama Widyalaya','RA','SPK KB','SPK TK','SPS','Taman Seminari','TK','TPA','Kursus/LKP','PKBM',
                    'Pondok Pesantren','SKB',
                    'Adi Widyalaya','Madyama Widyalaya','MI','MTs','PDF Wustha','SD','SDTK','SMP','SMPTK','SPK SD','SPK SMP','SPM Ula','SPM Wustha',
                    'MA','PDF Ulya','SLB','SMA','SMAg.K','SMAK','SMK','SMTK','SPK SMA','SPM Ulya','Utama Widyalaya','Uttama Dhammasekha']); // TK, KB, PKBM, dll

            // Koordinat yang Anda berikan (Presisi tinggi)
            $table->decimal('latitude', 14, 11); // Contoh: -7.4391605079
            $table->decimal('longitude', 14, 11); // Contoh: 111.3431193830

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lembagas');
    }
};
