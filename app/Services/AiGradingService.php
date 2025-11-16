<?php

namespace App\Services;

use App\Models\Attempt;
use App\Models\Quiz;

class AiGradingService
{
    public function __construct(
        private OpenAiClient $client,
    ) {
    }

    public function grade(Quiz $quiz, Attempt $attempt): array
    {
        if ($quiz->eval_type === 'numeric' && $quiz->numeric_tolerance !== null) {
            return $this->gradeNumeric($quiz, $attempt);
        }

        return $this->gradeWithOpenAi($quiz, $attempt);
    }

    private function gradeNumeric(Quiz $quiz, Attempt $attempt): array
    {
        $canonical = $this->extractNumber($quiz->canonical_answer);
        $student = $this->extractNumber($attempt->answer);

        if ($canonical === null || $student === null) {
            return $this->gradeWithOpenAi($quiz, $attempt);
        }

        $diff = abs($canonical - $student);
        $tol = (float) $quiz->numeric_tolerance;

        if ($diff == 0.0) {
            $label = 'UNDERSTOOD';
            $score = 100;
        } elseif ($diff <= $tol) {
            $label = 'REVISION_NEEDED';
            $score = 70;
        } else {
            $label = 'NOT_UNDERSTOOD';
            $score = 30;
        }

        return [
            'label' => $label,
            'score' => $score,
            'feedback' => [
                'canonical_answer' => $canonical,
                'student_answer' => $student,
                'difference' => $diff,
                'message' => 'Penilaian berdasarkan selisih angka dengan toleransi.',
            ],
            'ai_model' => 'local-numeric',
            'ai_raw' => null,
        ];
    }

    private function extractNumber(?string $text): ?float
    {
        if (!$text) {
            return null;
        }

        if (preg_match('/-?\d+(\.\d+)?/', $text, $m)) {
            return (float) $m[0];
        }

        return null;
    }

    private function gradeWithOpenAi(Quiz $quiz, Attempt $attempt): array
    {
        $system = <<<SYS
            Kamu adalah asisten penilai jawaban MATEMATIKA untuk anak SD di Indonesia.

            Fokus utama:
            - Ini BUKAN sekadar pilihan ganda / isi angka.
            - Siswa diharapkan MENJELASKAN langkah atau alasannya (essay pendek).
            - Kamu menilai:
            1) kebenaran konsep & hasil,
            2) kualitas penjelasan / langkah kerja,
            3) kejelasan bahasa.

            Aturan penting:

            1) Tentang penjelasan:
            - Jika jawaban siswa hanya berupa angka pendek, satu kata, atau satu frasa sangat singkat
            tanpa penjelasan (misal: "20", "hasilnya 20", "dua puluh"):
            - anggap jawaban BELUM LENGKAP,
            - minimal label = "REVISION_NEEDED",
            - score maksimal = 60,
            - feedback harus menjelaskan bahwa siswa perlu menuliskan langkah/penjelasan.

            2) Tentang typo / ejaan:
            - Jika langkah dan konsep matematika sudah benar, tetapi ada TYPO KECIL
            (misalnya "tujug" vs "tujuh", spasi kurang, huruf besar/kecil, kesalahan tulis ringan):
            - label TETAP boleh "UNDERSTOOD",
            - score boleh tinggi (misalnya 85–100),
            - sebutkan typo tersebut di feedback_points,
            - jangan turunkan label menjadi "REVISION_NEEDED" hanya karena ejaan kecil.

            3) Tentang kesalahan konsep:
            - Jika cara hitung atau penjelasan salah, atau jawaban menunjukkan miskonsepsi:
            - gunakan "REVISION_NEEDED" atau "NOT_UNDERSTOOD" sesuai tingkat kesalahan.

            Return HANYA JSON dengan struktur berikut:

            {
            "label": "UNDERSTOOD" | "REVISION_NEEDED" | "NOT_UNDERSTOOD",
            "score": 0-100 (integer),
            "feedback_summary": "ringkasan singkat dalam bahasa Indonesia",
            "feedback_points": [
                "poin feedback singkat 1",
                "poin feedback singkat 2"
            ],
            "misunderstanding_type": "string pendek yang menjelaskan jenis kesalahan jika ada",
            "flags": {
                "has_explanation": boolean,
                "only_final_answer": boolean
            }
            }

            Definisi label:
            - UNDERSTOOD: jawaban benar dan penjelasan menunjukkan pemahaman yang baik.
            - REVISION_NEEDED: ada sebagian pemahaman, tetapi penjelasan kurang, tidak lengkap, atau ada kesalahan konsep tertentu yang perlu diperbaiki.
            - NOT_UNDERSTOOD: jawaban salah total atau menunjukkan miskonsepsi serius.

            Jawab HANYA dengan JSON valid, tanpa teks lain.
        SYS;

        $acceptableAnswers = $quiz->acceptable_answers ?? [];

        $user = [
            'quiz_prompt' => $quiz->prompt,
            'canonical_answer' => $quiz->canonical_answer,
            'acceptable_answers' => $acceptableAnswers,
            'eval_type' => $quiz->eval_type,
            'student_answer' => $attempt->answer,
        ];

        $userPrompt = "Berikut data penilaian dalam format JSON:\n\n" .
            json_encode($user, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        $data = $this->client->chatJson($system, $userPrompt);

        $label = $data['label'] ?? 'NOT_UNDERSTOOD';
        $score = (int) ($data['score'] ?? 0);

        $label = match ($label) {
            'UNDERSTOOD', 'REVISION_NEEDED', 'NOT_UNDERSTOOD' => $label,
            default => 'NOT_UNDERSTOOD',
        };

        $score = max(0, min(100, $score));

        return [
            'label' => $label,
            'score' => $score,
            'feedback' => [
                'feedback_summary' => $data['feedback_summary'] ?? null,
                'feedback_points' => $data['feedback_points'] ?? [],
                'misunderstanding_type' => $data['misunderstanding_type'] ?? null,
                'flags' => $data['flags'] ?? null,
            ],
            'ai_model' => config('services.openai.model', 'gpt-4o-mini'),
            'ai_raw' => $data,
        ];
    }

}
