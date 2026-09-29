<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->foreignId('school_class_id')->nullable()->after('school_id')->constrained()->nullOnDelete();
            $table->foreignId('section_id')->nullable()->after('school_class_id')->constrained()->nullOnDelete();
            $table->string('roll_number')->nullable()->after('section_id');
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropConstrainedForeignId('school_class_id');
            $table->dropConstrainedForeignId('section_id');
            $table->dropColumn('roll_number');
        });
    }
};