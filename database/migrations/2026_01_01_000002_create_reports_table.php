<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->text('url');
            $table->string('domain')->index();
            $table->string('category'); // Phishing, Penipuan, Website Palsu, Pencurian Data, Tautan Mencurigakan
            $table->text('description');
            $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->string('submitter_ip')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
