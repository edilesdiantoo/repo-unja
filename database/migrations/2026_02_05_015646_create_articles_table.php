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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('author');
            $table->text('abstract');
            $table->string('keywords');
            $table->string('study_program'); // Prodi
            $table->year('year');
            $table->string('document_type'); // Skripsi, Tesis, dll
            $table->string('pembimbing_1')->nullable();
            $table->string('pembimbing_2')->nullable();
            $table->string('accreditation_level')->nullable(); // Sinta 1-6
            $table->enum('access_type', ['Fulltext', 'Abstrak']);
            $table->string('pdf_file');
            $table->string('cover_image')->nullable();
            $table->enum('status', ['pending', 'published', 'rejected', 'revision'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
