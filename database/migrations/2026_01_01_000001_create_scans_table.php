<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scans', function (Blueprint $table) {
            $table->id();
            $table->text('url');
            $table->string('domain')->index();
            $table->string('scheme')->default('https');
            $table->string('ip_address')->nullable();
            $table->integer('trust_score')->default(100);
            $table->enum('status', ['safe', 'warning', 'danger'])->default('safe');
            $table->json('ssl_info')->nullable();
            $table->json('rdap_info')->nullable();
            $table->json('threat_info')->nullable();
            $table->json('header_info')->nullable();
            $table->json('recommendations')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scans');
    }
};
