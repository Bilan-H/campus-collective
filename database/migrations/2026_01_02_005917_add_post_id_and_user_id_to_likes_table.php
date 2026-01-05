<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('likes', function (Blueprint $table) {
            if (!Schema::hasColumn('likes', 'post_id')) {
                $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            }
            if (!Schema::hasColumn('likes', 'user_id')) {
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            }
        });
    }

    public function down(): void
    {
        // 
    }
};

