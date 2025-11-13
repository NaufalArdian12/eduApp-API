<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('refresh_tokens', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->string('token_hash');
            $t->string('user_agent')->nullable();
            $t->string('ip')->nullable();
            $t->timestamp('expires_at')->nullable();
            $t->timestamps();
            $t->index('user_id');
        });
    }
    public function down(): void {
        Schema::dropIfExists('refresh_tokens');
    }
};
