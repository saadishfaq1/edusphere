<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->string('vehicle_number');
            $table->string('driver_name')->nullable();
            $table->string('driver_phone')->nullable();
            $table->unsignedInteger('capacity')->default(20);
            $table->timestamps();

            $table->unique(['school_id', 'vehicle_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};