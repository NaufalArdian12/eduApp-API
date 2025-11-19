<?php

namespace Database\Seeders;

use App\Models\Subject;
use App\Models\GradeLevel;
use App\Models\Topic;
use App\Models\Video;
use App\Models\Quiz;
use Illuminate\Database\Seeder;

class MovatoDemoSeeder extends Seeder
{
    public function run(): void
    {
        $subject = Subject::updateOrCreate(
            ['name' => 'Mathematics'],
            [
                'description' => 'Basic math lessons for Movato demo.',
                'is_active' => true,
            ]
        );

        $gradeLevel = GradeLevel::updateOrCreate(
            [
                'subject_id' => $subject->id,
                'grade_no' => 1,
            ],
            [
                'name' => 'Grade 1',
                'description' => 'Grade 1 basic math.',
                'order_index' => 1,
                'is_active' => true,
            ]
        );

        $topic = Topic::updateOrCreate(
            [
                'grade_level_id' => $gradeLevel->id,
                'title' => 'Addition Basics',
            ],
            [
                'description' => 'Learn how to add small numbers.',
                'order_index' => 1,
                'min_videos_before_assessment' => 1,
                'is_assessment_enabled' => true,
            ]
        );

        $video = Video::updateOrCreate(
            [
                'topic_id' => $topic->id,
                'title' => 'Addition with Objects',
            ],
            [
                'youtube_id' => 'dQw4w9WgXcQ',
                'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'duration_seconds' => 180,
                'order_index' => 1,
                'is_active' => true,
            ]
        );

        Quiz::updateOrCreate(
            [
                'topic_id' => $topic->id,
                'title' => 'Addition Quiz #1',
            ],
            [
                'prompt' => 'What is 2 + 3?',
                'canonical_answer' => '5',
                'acceptable_answers' => ['5', 'five'],
                'numeric_tolerance' => null,
                'eval_type' => 'exact',
                'rubric_id' => null,
                'order_index' => 1,
                'is_active' => true,
            ]
        );
    }
}
