<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fee_card_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->string('background_path');
            $table->json('positions');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fee_card_templates');
    }
};