<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
        });

        Schema::create('grade_levels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->unsignedTinyInteger('grade_no'); // 1..6
            $table->string('name'); // "Kelas 1"
            $table->text('description')->nullable();
            $table->integer('order_index')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['subject_id', 'grade_no']);
        });

        Schema::create('topics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grade_level_id')->constrained('grade_levels')->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('order_index')->nullable();
            $table->integer('min_videos_before_assessment')->default(0);
            $table->boolean('is_assessment_enabled')->default(true);
            $table->timestamps();

            $table->index(['grade_level_id', 'order_index']);
        });

        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('topic_id')->constrained('topics')->cascadeOnDelete();
            $table->string('title');
            $table->string('youtube_id');
            $table->string('youtube_url');
            $table->integer('duration_seconds')->nullable();
            $table->integer('order_index')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['topic_id', 'order_index']);
        });

        Schema::create('video_progress', function (Blueprint $table) {
            $table->id(); // optional pk
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('video_id')->constrained('videos')->cascadeOnDelete();
            $table->integer('seconds_watched')->default(0);
            $table->boolean('is_completed')->default(false);
            $table->timestamp('last_watched_at')->nullable();

            $table->unique(['user_id', 'video_id']);
        });

        Schema::create('rubrics', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->jsonb('thresholds_json')->nullable();
            $table->timestamps();
        });

        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('topic_id')->constrained('topics')->cascadeOnDelete();
            $table->string('title');
            $table->text('prompt');
            $table->text('canonical_answer')->nullable();
            $table->jsonb('acceptable_answers')->nullable();
            $table->double('numeric_tolerance')->nullable();
            $table->enum('eval_type', ['semantic', 'exact', 'numeric'])
                ->default('semantic');
            $table->foreignId('rubric_id')->nullable()->constrained('rubrics')->nullOnDelete();
            $table->integer('order_index')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['topic_id', 'order_index']);
        });

        Schema::create('attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('quiz_id')->constrained('quizzes')->cascadeOnDelete();

            $table->integer('attempt_no');
            $table->enum('status', ['submitted', 'graded', 'needs_revision', 'finalized'])
                ->default('submitted');

            $table->text('answer')->nullable();
            $table->enum('label', ['UNDERSTOOD', 'REVISION_NEEDED', 'NOT_UNDERSTOOD'])
                ->nullable();
            $table->integer('ai_score_percent')->nullable();
            $table->jsonb('ai_feedback')->nullable();
            $table->string('ai_model')->nullable();
            $table->jsonb('ai_raw')->nullable();

            $table->enum('manual_label', ['UNDERSTOOD', 'REVISION_NEEDED', 'NOT_UNDERSTOOD'])
                ->nullable();
            $table->text('manual_note')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('is_overridden')->default(false);

            $table->timestamps();

            $table->index('user_id');
            $table->index('quiz_id');
            $table->unique(['user_id', 'quiz_id', 'attempt_no']);
        });

        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('action', [
                'VIDEO_COMPLETED',
                'ASSESS_SUBMITTED',
                'ASSESS_PASSED',
                'LOGIN',
                'STREAK_CLAIMED',
            ]);
            $table->string('ref_type')->nullable(); // "video" | "quiz"
            $table->unsignedBigInteger('ref_id')->nullable();
            $table->integer('points')->default(0);
            $table->timestamp('occurred_at')->nullable();

            $table->index('user_id');
            $table->index('occurred_at');
            $table->index('action');
        });

        Schema::create('user_streaks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->integer('current_streak_days')->default(0);
            $table->integer('longest_streak_days')->default(0);
            $table->date('last_active_date')->nullable();
        });

        Schema::create('user_points', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->integer('total_points')->default(0);
            $table->integer('weekly_points')->default(0);
            $table->integer('monthly_points')->default(0);
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_points');
        Schema::dropIfExists('user_streaks');
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('attempts');
        Schema::dropIfExists('quizzes');
        Schema::dropIfExists('rubrics');
        Schema::dropIfExists('video_progress');
        Schema::dropIfExists('videos');
        Schema::dropIfExists('topics');
        Schema::dropIfExists('grade_levels');
        Schema::dropIfExists('subjects');
    }
};
