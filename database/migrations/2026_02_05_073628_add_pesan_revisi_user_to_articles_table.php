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
        Schema::table('articles', function (Blueprint $table) {
            // Tambahkan catatan_revisi dulu, baru pesan_revisi_user
            $table->text('catatan_revisi')->nullable()->after('status');
            $table->text('pesan_revisi_user')->nullable()->after('catatan_revisi');
        });
    }

    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn(['catatan_revisi', 'pesan_revisi_user']);
        });
    }
};
