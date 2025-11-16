<?php

namespace App\Services;

use App\Models\Attempt;
use App\Models\User;
use App\Repositories\AttemptRepository;
use App\Repositories\QuizRepository;

class AttemptService
{
    public function __construct(
        private AttemptRepository $attempts,
        private QuizRepository $quizzes,
        private AiGradingService $aiGrading,
        private GamificationService $gamification,
    ) {
    }

    public function submitAttempt(User $user, int $quizId, string $answer): Attempt
    {
        $quiz = $this->quizzes->find($quizId);

        if (!$quiz) {
            abort(404, 'Quiz not found');
        }

        $lastNo = $this->attempts->getLastAttemptNo($user->id, $quizId);

        $attempt = $this->attempts->create([
            'user_id' => $user->id,
            'quiz_id' => $quiz->id,
            'attempt_no' => $lastNo + 1,
            'status' => 'submitted',
            'answer' => $answer,
        ]);

        $result = $this->aiGrading->grade($quiz, $attempt);

        $attempt = $this->attempts->update($attempt, [
            'status' => 'graded',
            'label' => $result['label'],
            'ai_score_percent' => $result['score'],
            'ai_feedback' => $result['feedback'],
            'ai_model' => $result['ai_model'],
            'ai_raw' => $result['ai_raw'],
        ]);

        $points = 5;
        if ($result['label'] === 'UNDERSTOOD') {
            $points += 10;

            $this->gamification->rewardActivity(
                $user,
                'ASSESS_PASSED',
                'quiz',
                $quiz->id,
                $points
            );
        } else {
            $this->gamification->rewardActivity(
                $user,
                'ASSESS_SUBMITTED',
                'quiz',
                $quiz->id,
                $points
            );
        }

        return $attempt->fresh(['quiz.topic']);
    }
}
