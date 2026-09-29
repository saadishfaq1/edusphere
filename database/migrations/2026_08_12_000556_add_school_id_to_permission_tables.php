<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $teamForeignKey = config('permission.column_names.team_foreign_key');

        Schema::table('roles', function (Blueprint $table) use ($teamForeignKey) {
            if (! Schema::hasColumn('roles', $teamForeignKey)) {
                $table->unsignedBigInteger($teamForeignKey)->nullable()->after('id');
            }
        });

        Schema::table('model_has_roles', function (Blueprint $table) use ($teamForeignKey) {
            if (! Schema::hasColumn('model_has_roles', $teamForeignKey)) {
                $table->unsignedBigInteger($teamForeignKey)->nullable();
            }
        });

        Schema::table('model_has_permissions', function (Blueprint $table) use ($teamForeignKey) {
            if (! Schema::hasColumn('model_has_permissions', $teamForeignKey)) {
                $table->unsignedBigInteger($teamForeignKey)->nullable();
            }
        });
    }

    public function down(): void
    {
        $teamForeignKey = config('permission.column_names.team_foreign_key');

        Schema::table('roles', fn (Blueprint $table) => $table->dropColumn($teamForeignKey));
        Schema::table('model_has_roles', fn (Blueprint $table) => $table->dropColumn($teamForeignKey));
        Schema::table('model_has_permissions', fn (Blueprint $table) => $table->dropColumn($teamForeignKey));
    }
};