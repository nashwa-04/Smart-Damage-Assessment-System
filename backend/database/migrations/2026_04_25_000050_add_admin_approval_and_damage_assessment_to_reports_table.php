<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->string('admin_approval_status', 20)->nullable()->after('status')->comment('pending, approved, rejected');
            $table->unsignedTinyInteger('admin_damage_score')->nullable()->after('ai_damage_score')->comment('Admin damage assessment 1-10');
            $table->text('admin_notes')->nullable()->after('ai_analysis')->comment('Admin notes and observations');
            $table->unsignedBigInteger('approved_by')->nullable()->after('admin_approval_status')->comment('Admin user ID who approved/rejected');
            
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropColumn(['admin_approval_status', 'admin_damage_score', 'admin_notes', 'approved_by']);
        });
    }
};
