<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('likes', function (Blueprint $table) {
            $table->foreignId('post_id')->after('id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->after('post_id')->constrained()->cascadeOnDelete();
            $table->unique(['post_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::table('likes', function (Blueprint $table) {
            $table->dropUnique(['post_id', 'user_id']);
            $table->dropConstrainedForeignId('post_id');
            $table->dropConstrainedForeignId('user_id');
        });
    }
};

