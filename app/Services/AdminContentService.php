<?php

namespace App\Services;

use App\Models\Subject;
use App\Models\GradeLevel;
use App\Models\Topic;
use App\Models\Video;
use App\Models\Quiz;
use App\Models\Rubric;
use App\Repositories\SubjectRepository;
use App\Repositories\GradeLevelRepository;
use App\Repositories\TopicRepository;
use App\Repositories\VideoRepository;
use App\Repositories\RubricRepository;
use App\Repositories\QuizRepository;
use Illuminate\Support\Collection;

class AdminContentService
{
    public function __construct(
        private SubjectRepository $subjects,
        private GradeLevelRepository $gradeLevels,
        private TopicRepository $topics,
        private VideoRepository $videos,
        private QuizRepository $quizzes,
        private RubricRepository $rubrics
    ) {
    }

    /** ===== SUBJECTS ===== */
    public function listSubjects(): Collection
    {
        return $this->subjects->all();
    }

    public function createSubject(array $data): Subject
    {
        return $this->subjects->create($data);
    }

    public function updateSubject(Subject $subject, array $data): Subject
    {
        return $this->subjects->update($subject, $data);
    }

    public function deleteSubject(Subject $subject): void
    {
        $this->subjects->delete($subject);
    }

    /** ===== GRADE LEVELS ===== */
    public function listGradeLevels(?int $subjectId = null): Collection
    {
        return $this->gradeLevels->all($subjectId);
    }

    public function createGradeLevel(array $data): GradeLevel
    {
        return $this->gradeLevels->create($data);
    }

    public function updateGradeLevel(GradeLevel $gradeLevel, array $data): GradeLevel
    {
        return $this->gradeLevels->update($gradeLevel, $data);
    }

    public function deleteGradeLevel(GradeLevel $gradeLevel): void
    {
        $this->gradeLevels->delete($gradeLevel);
    }

    /** ===== TOPICS ===== */
    public function listTopics(?int $gradeLevelId = null): Collection
    {
        return $this->topics->all($gradeLevelId);
    }

    public function createTopic(array $data): Topic
    {
        return $this->topics->create($data);
    }

    public function updateTopic(Topic $topic, array $data): Topic
    {
        return $this->topics->update($topic, $data);
    }

    public function deleteTopic(Topic $topic): void
    {
        $this->topics->delete($topic);
    }

    /** ===== VIDEOS ===== */
    public function listVideos(?int $topicId = null): Collection
    {
        return $this->videos->all($topicId);
    }

    public function createVideo(array $data): Video
    {
        return $this->videos->create($data);
    }

    public function updateVideo(Video $video, array $data): Video
    {
        return $this->videos->update($video, $data);
    }

    public function deleteVideo(Video $video): void
    {
        $this->videos->delete($video);
    }

    /** ===== QUIZZES ===== */
    public function listQuizzes(?int $topicId = null): Collection
    {
        return $this->quizzes->all($topicId);
    }

    public function createQuiz(array $data): Quiz
    {
        return $this->quizzes->create($data);
    }

    public function updateQuiz(Quiz $quiz, array $data): Quiz
    {
        return $this->quizzes->update($quiz, $data);
    }

    public function deleteQuiz(Quiz $quiz): void
    {
        $this->quizzes->delete($quiz);
    }

    /** ===== RUBRICS ===== */

    public function listRubrics()
    {
        return $this->rubrics->all();
    }

    public function createRubric(array $data)
    {
        return $this->rubrics->create($data);
    }

    public function updateRubric(Rubric $rubric, array $data)
    {
        return $this->rubrics->update($rubric, $data);
    }

    public function deleteRubric(Rubric $rubric)
    {
        $this->rubrics->delete($rubric);
    }

}
