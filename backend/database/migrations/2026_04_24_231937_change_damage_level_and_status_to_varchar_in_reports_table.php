<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->string('ai_damage_level', 50)->nullable()->change();
            $table->string('status', 50)->default('pending')->change();
        });

        DB::statement("UPDATE reports SET ai_damage_level = 'minor' WHERE ai_damage_level = 'low'");
        DB::statement("UPDATE reports SET ai_damage_level = 'moderate' WHERE ai_damage_level = 'medium'");
        DB::statement("UPDATE reports SET ai_damage_level = 'severe' WHERE ai_damage_level = 'high'");
        DB::statement("UPDATE reports SET status = 'pending_approval' WHERE status = 'pending' AND ai_damage_level IS NOT NULL");
        DB::statement("UPDATE reports SET status = 'pending_approval' WHERE status = 'processing'");
    }

    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->enum('ai_damage_level', ['low', 'medium', 'high', 'critical'])->nullable()->change();
            $table->enum('status', ['pending', 'processing', 'completed', 'rejected'])->default('pending')->change();
        });
    }
};